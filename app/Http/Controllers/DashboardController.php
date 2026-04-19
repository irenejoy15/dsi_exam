<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use App\Http\Resources\ProductSalesResource;
use App\Http\Resources\CategorySalesResource;
use App\Http\Resources\TotalAmountResource;
use App\Http\Resources\AmountByMonthResource;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Percentage;

class DashboardController extends Controller
{
    public function index()
    {
        $year = Carbon::now()->year;
        return view('dashboard.index',compact('year'));
    }

    public function getProductsAmount($year)
    {
        $product_sales = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_quantity')
            ->groupBy('products.name')
            ->whereYear('orders.created_at', $year)
            ->orderBy('total_quantity', 'desc')
            ->get();
        return ProductSalesResource::collection($product_sales);
    }

    public function getCategorySold($year)
    {
        $category_sales = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(order_items.quantity) as total_quantity')
            ->groupBy('categories.name')
            ->whereYear('orders.created_at', $year)
            ->orderBy('total_quantity', 'desc')
            ->get();
        return CategorySalesResource::collection($category_sales);
    }

    public function getProductsAmountByYear($year)
    {
        $product_sales = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(orders.total_amount) as total_amount')
            ->whereYear('orders.created_at', $year)
            ->groupBy('products.name')
            ->orderBy('total_amount', 'desc')
            ->get();
        return TotalAmountResource::collection($product_sales);
    }

    public function getAmountByMonth($year)
    {
        $monthly_sales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_amount')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        return AmountByMonthResource::collection($monthly_sales);
    }

    public function exportExcel($year)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $orders = Order::whereYear('created_at', $year)->get();
        $sheet->setCellValue('A6', 'Order ID');
        $sheet->setCellValue('B6', 'Customer Name');
        $sheet->setCellValue('C6', 'Products');
        $sheet->setCellValue('D6', 'Qty');
        $sheet->setCellValue('E6', 'Total Amount');
        $sheet->setCellValue('F6', 'Created At');
        $total_amount = 0;
        $total_qty = 0;
        $row = 7; // Start from row 7 to leave space for header and logo
        foreach ($orders as $order):
            // implode for new line in products column
            $products = $order->orderItems->map(function($item) {
                return $item->product->name . ' (x' . $item->quantity . ')';
            })->implode("\n");

            $sheet->setCellValue('A' . $row, $order->id);
            $sheet->setCellValue('B' . $row, $order->user->name);
            $sheet->setCellValue('C' . $row, $products);
            $sheet->setCellValue('D' . $row, $order->orderItems->sum('quantity'));
            $sheet->setCellValue('E' . $row, number_format($order->total_amount, 2));
            $sheet->setCellValue('F' . $row, Carbon::parse($order->created_at)->format('Y-m-d'));
            $total_amount += $order->total_amount;
            $total_qty += $order->orderItems->sum('quantity');
            $row++;
        endforeach;
        // A6:F6 for header row Desgin font bold and center alignment and font size 14  
        $sheet->getStyle('A6:F6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A6:F6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // PUT IMAGE LOGO IN A1 CELL
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('assets/images/icon/logo.png')); // put your logo path here
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(0);
        $drawing->setOffsetY(0);
        $drawing->setWorksheet($sheet);

        // Auto-size columns
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // TOTAL AMOUNT AND TOTAL QTY IN LAST ROW WITH BOLD FONT and Vertical Alignment Center
        $sheet->setCellValue('D' . $row, 'Total Qty: ' . $total_qty);
        $sheet->setCellValue('E' . $row, 'Total Amount: ' . number_format($total_amount, 2));
        $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $row . ':E' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        $sheet->getStyle('A6:F' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('C7:C' . ($row - 1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('A6:F' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A6:F6')->getFont()->setBold(true);
        $sheet->getStyle('A6:F' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E7:E' . ($row - 1))->getNumberFormat();
        $writer = new Xlsx($spreadsheet);
        $fileName = 'orders_' . $year . '.xlsx';
        $writer->save($fileName);
        return response()->download($fileName)->deleteFileAfterSend(true);

    }
}

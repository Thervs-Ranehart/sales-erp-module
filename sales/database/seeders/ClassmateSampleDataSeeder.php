<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassmateSampleDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedCustomers();
            $this->seedProducts();
            $this->seedPricingRules();
            $this->seedQuotations();
            $this->seedSalesOrders();
            $this->seedInvoices();
            $this->seedCommunicationLogs();
            $this->seedServiceContracts();
            $this->seedWarrantyRecords();
        });

        $this->command?->info('Classmate sample data imported without replacing existing records.');
    }

    private function seedCustomers(): void
    {
        $customers = [
            ['Fiona', 'Adelaide', 'fiona.adelaide@gmail.com', '09447712782', '971 Garcia St., Barangay 10', 'Electronics', 'Active', 3],
            ['Anica', 'Ella', 'anica.ella@gmail.com', '09981836553', '549 Torres St., Barangay 7', 'Electronics', 'Active', 1],
            ['Bea', 'Larrise', 'bea.larrise@gmail.com', '09330530419', '39 Mabini St., Barangay 6', 'Electronics', 'Active', 1],
            ['Thervin', 'Bandril', 'thervin.bandril@gmail.com', '09197402358', '565 Rizal St., Barangay 28', 'Electronics', 'Active', 4],
            ['John', 'Dela Cruz', 'john.delacruz@gmail.com', '09339701014', '646 Bonifacio St., Barangay 41', 'Electronics', 'Archived', 1],
            ['Adelaide', 'Rivera', 'adelaide.rivera@email.com', '09171234567', 'Quezon City, Metro Manila', 'Prefers email communication and product updates.', 'Active', 1],
            ['Ella', 'Santos', 'ella.santos@email.com', '09182345678', 'Makati City, Metro Manila', 'Interested in premium products and loyalty rewards.', 'Active', 2],
            ['Larisse', 'Cruz', 'larisse.cruz@email.com', '09193456789', 'Pasig City, Metro Manila', 'Prefers SMS notifications for orders and promotions.', 'Active', 3],
            ['Ranehart', 'Villanueva', 'ranehart.villanueva@email.com', '09204567890', 'Taguig City, Metro Manila', 'Prefers phone support and fast response service.', 'Active', 4],
            ['Joshua', 'Garcia', 'joshua.garcia@email.com', '09215678901', 'Manila City', 'Interested in electronics and discounted offers.', 'Active', 5],
            ['Sophia', 'Mendoza', 'sophia.mendoza@email.com', '09226789012', 'Pasay City', 'Prefers online transactions and digital invoices.', 'Active', 1],
            ['Nathan', 'Reyes', 'nathan.reyes@email.com', '09237890123', 'Cavite City', 'Interested in warranty services and product maintenance.', 'Active', 2],
            ['Isabella', 'Torres', 'isabella.torres@email.com', '09248901234', 'Bacoor, Cavite', 'Prefers email updates and seasonal promotions.', 'Active', 3],
            ['Daniel', 'Navarro', 'daniel.navarro@email.com', '09259012345', 'Imus, Cavite', 'Interested in business packages and bulk purchases.', 'Active', 4],
            ['Mia', 'Castillo', 'mia.castillo@email.com', '09260123456', 'Dasmarinas, Cavite', 'Prefers chat support and quick order tracking.', 'Active', 5],
            ['ANICA', 'CUENO', 'anicaellacueno07@gmail.com', '09814541312', '141 MAGDALO ST. BUNA LEJOS II', 'Prefers email communication and product updates.', 'Active', 1],
        ];

        foreach ($customers as [$firstName, $lastName, $email, $contact, $address, $preferences, $status, $regionId]) {
            $this->firstId('customers', 'customer_id', ['email' => $email], [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'contact_no' => $contact,
                'address' => $address,
                'preferences' => $preferences,
                'customer_status' => $status,
                'region_id' => $regionId,
                'created_at' => '2026-01-10 10:00:00',
                'updated_at' => '2026-01-10 10:00:00',
            ]);
        }
    }

    private function seedProducts(): void
    {
        $products = [
            ['Wireless Mouse', 'Electronics', 'Wireless Mouse - Electronics item', 641.32, 89],
            ['Mechanical Keyboard', 'Electronics', 'Mechanical Keyboard - Electronics item', 1981.03, 209],
            ['27-inch Monitor', 'Electronics', '27-inch Monitor - Electronics item', 9396.17, 262],
            ['USB-C Hub', 'Electronics', 'USB-C Hub - Electronics item', 834.82, 83],
            ['Bluetooth Speaker', 'Electronics', 'Bluetooth Speaker - Electronics item', 1599.52, 271],
            ['Gaming Headset', 'Electronics', 'High-quality gaming headset with microphone', 2499.00, 150],
            ['Webcam HD', 'Electronics', '1080p USB webcam for online meetings', 1799.00, 119],
            ['External SSD 1TB', 'Electronics', 'Portable 1TB USB-C solid state drive', 5499.00, 74],
            ['Wireless Earbuds', 'Electronics', 'Bluetooth wireless earbuds with charging case', 2199.00, 179],
            ['Laptop Stand', 'Electronics', 'Adjustable aluminum laptop stand', 999.00, 128],
            ['Power Bank 20000mAh', 'Electronics', 'Fast charging portable power bank', 1499.00, 160],
            ['Smart Watch', 'Electronics', 'Fitness and health tracking smartwatch', 3999.00, 94],
            ['Wireless Charger', 'Electronics', '15W fast wireless charging pad', 899.00, 210],
            ['Portable Projector', 'Electronics', 'Mini HD portable projector', 6999.00, 45],
            ['Gaming Chair', 'Furniture', 'Ergonomic gaming chair with lumbar support', 8999.00, 34],
            ['Office Desk', 'Furniture', 'Modern wooden office desk with cable management.', 5999.00, 40],
            ['Printer Paper A4 (500 Sheets)', 'Office Supplies', 'High-quality A4 bond paper suitable for laser and inkjet printers.', 280.00, 350],
            ['Ergonomic Office Chair', 'Furniture', 'Adjustable ergonomic office chair with lumbar support.', 7499.00, 28],
            ['Air Purifier', 'Home Appliances', 'HEPA air purifier designed for home and office use.', 4599.00, 52],
            ['Coffee Maker', 'Kitchen Appliances', 'Automatic drip coffee maker with 12-cup capacity.', 3299.00, 38],
            ['Whiteboard 4x6 ft', 'Office Supplies', 'Magnetic dry-erase whiteboard with aluminum frame.', 2899.00, 24],
            ['Fire Extinguisher', 'Safety Equipment', 'ABC dry chemical fire extinguisher for office and home use.', 1899.00, 65],
            ['LED Desk Lamp', 'Office Equipment', 'Adjustable LED desk lamp with touch controls and USB charging port.', 1299.00, 95],
            ['Document Shredder', 'Office Equipment', 'Cross-cut document shredder for secure disposal of confidential files.', 4599.00, 22],
            ['Network Switch 8-Port', 'Networking', '8-port Gigabit Ethernet network switch for office networking.', 2399.00, 48],
        ];

        foreach ($products as [$name, $category, $description, $price, $stock]) {
            $this->firstId('products', 'product_id', ['product_name' => $name], [
                'category' => $category,
                'description' => $description,
                'unit_price' => $price,
                'stock_quantity' => $stock,
                'product_status' => 'Active',
                'created_at' => '2026-01-03 09:00:00',
                'updated_at' => '2026-01-03 09:00:00',
            ]);
        }
    }

    private function seedPricingRules(): void
    {
        $rules = [
            ['Regular Customer Discount', 'Percentage', 5.00, '2026-07-01', '2026-12-31', 'Active'],
            ['VIP Customer Discount', 'Percentage', 15.00, '2026-07-01', '2026-12-31', 'Active'],
            ['Holiday Sale Promotion', 'Percentage', 20.00, '2026-11-01', '2026-12-31', 'Active'],
            ['Fixed Amount Discount', 'Fixed', 500.00, '2026-08-01', '2026-09-30', 'Active'],
            ['Corporate Partner Rate', 'Percentage', 10.00, '2026-07-01', '2026-07-31', 'Active'],
            ['7.7 Sales Promotion', 'Fixed', 500.00, '2026-07-07', '2026-07-09', 'Active'],
            ['Testing Discount Rule', 'Percentage', 10.00, '2026-07-28', '2026-07-29', 'Inactive'],
        ];

        foreach ($rules as [$name, $type, $value, $start, $end, $status]) {
            $this->firstId('pricing_rules', 'pricing_rule_id', ['rule_name' => $name], [
                'discount_type' => $type,
                'discount_value' => $value,
                'tax_rate' => 12,
                'start_date' => $start,
                'end_date' => $end,
                'status' => $status,
            ]);
        }
    }

    private function seedQuotations(): void
    {
        $adminId = $this->employeeId('admin');
        $bulkRuleId = $this->pricingRuleId('Bulk Order Discount');
        $quotations = [
            ['QT-00001', 'bea.larrise@gmail.com', '2026-07-27', '2026-08-26', 14498.00, 2174.70, 1478.80, 13802.10, 'sent'],
            ['QT-00002', 'anicaellacueno07@gmail.com', '2026-07-28', '2026-08-27', 14498.00, 2174.70, 1478.80, 13802.10, 'accepted'],
            ['QT-00003', 'fiona.adelaide@gmail.com', '2026-07-28', '2026-08-27', 2199.00, 329.85, 224.30, 2093.45, 'draft'],
        ];

        foreach ($quotations as [$number, $email, $date, $validUntil, $subtotal, $discount, $tax, $total, $status]) {
            $this->firstId('quotations', 'quotation_id', ['quotation_number' => $number], [
                'customer_id' => $this->customerId($email),
                'employee_id' => $adminId,
                'pricing_rule_id' => $bulkRuleId,
                'quotation_date' => $date,
                'valid_until' => $validUntil,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping_fee' => 0,
                'total_amount' => $total,
                'quotation_status' => $status,
                'created_at' => "$date 00:00:00",
                'updated_at' => "$date 00:00:00",
            ]);
        }

        $items = [
            ['QT-00001', 'Gaming Chair', 1, 8999.00],
            ['QT-00001', 'External SSD 1TB', 1, 5499.00],
            ['QT-00002', 'External SSD 1TB', 1, 5499.00],
            ['QT-00002', 'Gaming Chair', 1, 8999.00],
            ['QT-00003', 'Wireless Earbuds', 1, 2199.00],
        ];

        foreach ($items as [$quotationNumber, $productName, $quantity, $price]) {
            $quotationId = $this->quotationId($quotationNumber);
            $productId = $this->productId($productName);
            $this->firstId('quotation_items', 'quotation_item_id', [
                'quotation_id' => $quotationId,
                'product_id' => $productId,
            ], [
                'quantity' => $quantity,
                'unit_price' => $price,
                'discount' => 0,
                'subtotal' => $price * $quantity,
            ]);
        }
    }

    private function seedSalesOrders(): void
    {
        $adminId = $this->employeeId('admin');
        $orders = [
            ['SO-00001', 'fiona.adelaide@gmail.com', 2, '2026-07-27', null, null, 'shipped', 834.82, 125.22, 85.15, 794.75, null],
            ['SO-00002', 'anica.ella@gmail.com', 5, '2026-07-27', null, null, 'delivered', 6498.00, 649.80, 701.78, 6549.98, null],
            ['SO-00003', 'thervin.bandril@gmail.com', 5, '2026-07-27', null, null, 'pending', 5498.00, 549.80, 593.78, 5541.98, null],
            ['SO-00004', 'bea.larrise@gmail.com', 5, '2026-07-27', null, null, 'shipped', 16097.52, 1609.75, 1738.53, 16226.30, 'QT-00001'],
            ['SO-00005', 'john.delacruz@gmail.com', 1, '2026-07-27', null, null, 'processed', 2498.52, 124.93, 284.83, 2658.42, null],
            ['SO-00006', 'bea.larrise@gmail.com', 2, '2026-07-27', null, null, 'shipped', 12998.00, 1949.70, 1325.80, 12374.10, null],
            ['SO-00007', 'anica.ella@gmail.com', null, '2026-05-22', null, null, 'shipped', 7197.00, 0, 863.64, 8060.64, null],
            ['SO-00008', 'anica.ella@gmail.com', null, '2026-06-24', null, null, 'pending', 6198.00, 0, 743.76, 6941.76, null],
            ['SO-00009', 'ella.santos@email.com', null, '2026-04-29', null, null, 'delivered', 3698.00, 0, 443.76, 4141.76, null],
            ['SO-00010', 'daniel.navarro@email.com', null, '2026-04-07', null, null, 'delivered', 10312.46, 0, 1237.50, 11549.96, null],
            ['SO-00011', 'adelaide.rivera@email.com', null, '2026-05-03', 'Cash', 'Paid', 'delivered', 2798.00, 0, 335.76, 3133.76, null],
            ['SO-00012', 'larisse.cruz@email.com', null, '2026-05-11', 'Credit Card', 'Paid', 'shipped', 24893.17, 0, 2987.18, 27880.35, null],
            ['SO-00013', 'joshua.garcia@email.com', null, '2026-05-18', 'Bank Transfer', 'Pending', 'pending', 3299.00, 0, 395.88, 3694.88, null],
            ['SO-00014', 'nathan.reyes@email.com', 5, '2026-05-24', 'Cash', 'Paid', 'processed', 11250.00, 1125.00, 1215.00, 11340.00, null],
            ['SO-00015', 'daniel.navarro@email.com', null, '2026-05-30', 'Online Payment', 'Paid', 'delivered', 6750.00, 337.50, 769.50, 7182.00, null],
            ['SO-00016', 'adelaide.rivera@email.com', null, '2026-02-03', 'Cash', 'Paid', 'delivered', 5299.00, 0, 635.88, 5934.88, null],
            ['SO-00017', 'larisse.cruz@email.com', 2, '2026-02-08', 'Credit Card', 'Paid', 'shipped', 8450.00, 845.00, 912.60, 8517.60, null],
            ['SO-00018', 'joshua.garcia@email.com', null, '2026-02-12', 'Online Payment', 'Pending', 'pending', 3799.00, 0, 455.88, 4254.88, null],
            ['SO-00019', 'nathan.reyes@email.com', null, '2026-02-18', 'Cash', 'Paid', 'processed', 4599.00, 0, 551.88, 5150.88, null],
            ['SO-00020', 'daniel.navarro@email.com', null, '2026-02-22', 'Cash', 'Paid', 'delivered', 6899.00, 0, 827.88, 7726.88, null],
            ['SO-00021', 'anicaellacueno07@gmail.com', 2, '2026-07-28', null, null, 'shipped', 9679.03, 1451.85, 987.26, 9214.44, 'QT-00002'],
            ['SO-00022', 'anicaellacueno07@gmail.com', 2, '2026-07-28', null, null, 'delivered', 9396.17, 1409.43, 958.41, 8945.15, 'QT-00003'],
        ];

        foreach ($orders as [$number, $email, $ruleId, $date, $paymentMethod, $paymentStatus, $status, $subtotal, $discount, $tax, $total, $quotationNumber]) {
            $this->firstId('sales_orders', 'order_id', ['order_number' => $number], [
                'quotation_id' => $quotationNumber ? $this->quotationId($quotationNumber) : null,
                'customer_id' => $this->customerId($email),
                'employee_id' => $adminId,
                'pricing_rule_id' => $ruleId,
                'order_date' => $date,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'order_status' => $status,
                'warehouse' => null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping_fee' => 0,
                'total_amount' => $total,
                'created_at' => "$date 12:00:00",
                'updated_at' => "$date 12:00:00",
            ]);
        }

        // These are the only order lines that can be reconstructed exactly from
        // the supplied invoice_items rows.
        $items = [
            ['SO-00007', 'Wireless Earbuds', 1, 2199.00],
            ['SO-00007', 'Smart Watch', 1, 3999.00],
            ['SO-00007', 'Laptop Stand', 1, 999.00],
            ['SO-00001', 'USB-C Hub', 1, 834.82],
            ['SO-00004', 'External SSD 1TB', 1, 5499.00],
            ['SO-00004', 'Bluetooth Speaker', 1, 1599.52],
            ['SO-00004', 'Gaming Chair', 1, 8999.00],
            ['SO-00011', 'Webcam HD', 1, 1799.00],
            ['SO-00011', 'Laptop Stand', 1, 999.00],
            ['SO-00022', '27-inch Monitor', 1, 9396.17],
        ];

        foreach ($items as [$orderNumber, $productName, $quantity, $price]) {
            $this->orderItemId($orderNumber, $productName, $quantity, $price);
        }
    }

    private function seedInvoices(): void
    {
        $adminId = $this->employeeId('admin');
        $invoices = [
            ['INV-00001', 'SO-00007', '2026-07-27', 'GCash', 'Paid', 7197.00, 0, 863.64, 8060.64],
            ['INV-00002', 'SO-00001', '2026-07-27', 'Cash', 'Paid', 834.82, 125.22, 85.15, 794.75],
            ['INV-00003', 'SO-00004', '2026-07-27', 'Cash', 'Pending', 16097.52, 1609.75, 1738.53, 16226.30],
            ['INV-00004', 'SO-00011', '2026-07-27', 'Cash', 'Pending', 2798.00, 0, 335.76, 3133.76],
            ['INV-00005', 'SO-00022', '2026-07-28', 'Credit Card', 'Paid', 9396.17, 1409.43, 958.41, 8945.15],
        ];

        foreach ($invoices as [$number, $orderNumber, $date, $method, $status, $subtotal, $discount, $tax, $total]) {
            $this->firstId('invoices', 'invoice_id', ['invoice_number' => $number], [
                'order_id' => $this->orderId($orderNumber),
                'employee_id' => $adminId,
                'invoice_date' => $date,
                'payment_method' => $method,
                'payment_status' => $status,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping_fee' => 0,
                'total_amount' => $total,
                'created_at' => "$date 12:00:00",
                'updated_at' => "$date 12:00:00",
            ]);
        }

        $items = [
            ['INV-00001', 'SO-00007', 'Wireless Earbuds', 1, 2199.00],
            ['INV-00001', 'SO-00007', 'Smart Watch', 1, 3999.00],
            ['INV-00001', 'SO-00007', 'Laptop Stand', 1, 999.00],
            ['INV-00002', 'SO-00001', 'USB-C Hub', 1, 834.82],
            ['INV-00003', 'SO-00004', 'External SSD 1TB', 1, 5499.00],
            ['INV-00003', 'SO-00004', 'Bluetooth Speaker', 1, 1599.52],
            ['INV-00003', 'SO-00004', 'Gaming Chair', 1, 8999.00],
            ['INV-00004', 'SO-00011', 'Webcam HD', 1, 1799.00],
            ['INV-00004', 'SO-00011', 'Laptop Stand', 1, 999.00],
            ['INV-00005', 'SO-00022', '27-inch Monitor', 1, 9396.17],
        ];

        foreach ($items as [$invoiceNumber, $orderNumber, $productName, $quantity, $price]) {
            $invoiceId = (int) DB::table('invoices')->where('invoice_number', $invoiceNumber)->value('invoice_id');
            $productId = $this->productId($productName);
            $this->firstId('invoice_items', 'invoice_item_id', [
                'invoice_id' => $invoiceId,
                'product_id' => $productId,
            ], [
                'order_item_id' => $this->orderItemId($orderNumber, $productName, $quantity, $price),
                'quantity' => $quantity,
                'unit_price' => $price,
                'subtotal' => $price * $quantity,
            ]);
        }
    }

    private function seedCommunicationLogs(): void
    {
        $logs = [
            ['AUTO-COMM-001', 'fiona.adelaide@gmail.com', 'support', 3, '2026-07-01 09:30:00', 'Email', 'Welcome Email', 'Sent a welcome email introducing company products and services.', '2026-07-08 09:30:00', 'Pending', 'Normal', 'None', 'Customer Retained'],
            ['AUTO-COMM-002', 'anica.ella@gmail.com', 'salesmanager', 1, '2026-07-05 14:15:00', 'Phone Call', 'Product Inquiry', 'Answered customer questions regarding product pricing and availability.', '2026-07-12 14:15:00', 'Pending', 'High', 'Weekly', 'Pending'],
            ['AUTO-COMM-003', 'bea.larrise@gmail.com', 'admin', null, '2026-07-10 10:45:00', 'SMS', 'Order Delivery', 'Notified customer that the order has been delivered successfully.', null, 'Completed', 'Normal', 'None', 'Satisfied'],
            ['AUTO-COMM-004', 'thervin.bandril@gmail.com', 'salesrep', 1, '2026-07-15 16:00:00', 'Email', 'Payment Reminder', 'Sent reminder for an unpaid invoice due next week.', '2026-07-22 16:00:00', 'Pending', 'Urgent', 'Weekly', 'Awaiting Payment'],
            ['AUTO-COMM-005', 'john.delacruz@gmail.com', 'salesmanager', 1, '2026-07-20 11:30:00', 'Email', 'Loyalty Program Invitation', 'Presented loyalty program benefits and exclusive membership offers.', '2026-07-27 00:00:00', 'Pending', 'High', 'Yearly', 'Interested'],
        ];

        foreach ($logs as [$key, $email, $username, $agentId, $date, $channel, $subject, $notes, $followUp, $status, $priority, $recurrence, $outcome]) {
            $this->firstId('communication_logs', 'communication_id', ['automation_key' => $key], [
                'customer_id' => $this->customerId($email),
                'employee_id' => $this->employeeId($username),
                'communication_date' => $date,
                'communication_channel' => $channel,
                'subject' => $subject,
                'notes' => $notes,
                'follow_up_date' => $followUp,
                'communication_status' => $status,
                'priority' => $priority,
                'recurrence' => $recurrence,
                'retention_outcome' => $outcome,
            ]);
        }
    }

    private function seedServiceContracts(): void
    {
        $contracts = [
            ['SC-2026-001', 'fiona.adelaide@gmail.com', 'Wireless Mouse', 'Warranty Service', '2026-07-01', '2027-07-01', 10, 0],
            ['SC-2026-002', 'anica.ella@gmail.com', '27-inch Monitor', 'Premium Support', '2026-07-05', '2027-07-05', 20, 2],
            ['SC-2026-003', 'bea.larrise@gmail.com', 'Bluetooth Speaker', 'Maintenance Service', '2026-07-10', '2027-07-10', 15, 1],
            ['SC-2026-004', 'thervin.bandril@gmail.com', 'Webcam HD', 'Warranty Extension', '2026-07-15', '2027-07-15', 5, 0],
            ['SC-2026-005', 'john.delacruz@gmail.com', 'Wireless Earbuds', 'Technical Support', '2026-07-20', '2027-07-20', 12, 3],
        ];

        foreach ($contracts as [$number, $email, $product, $type, $start, $end, $limit, $used]) {
            $this->firstId('service_contracts', 'contract_id', ['contract_number' => $number], [
                'customer_id' => $this->customerId($email),
                'product_id' => $this->productId($product),
                'service_type' => $type,
                'service_start' => $start,
                'service_end' => $end,
                'contract_status' => 'Active',
                'service_limit' => $limit,
                'services_used' => $used,
                'created_at' => '2026-07-27 23:18:21',
                'updated_at' => '2026-07-27 23:18:21',
            ]);
        }
    }

    private function seedWarrantyRecords(): void
    {
        $records = [
            ['WR-00001', 'SO-00001', 'Wireless Mouse', '2026-07-27', '2027-07-27', 'Active', null],
            ['WR-00002', 'SO-00002', '27-inch Monitor', '2026-07-27', '2027-07-27', 'Active', null],
            ['WR-00003', 'SO-00003', 'Bluetooth Speaker', '2026-07-27', '2028-07-27', 'Active', null],
            ['WR-00004', 'SO-00004', 'Webcam HD', '2026-07-27', '2027-12-27', 'Active', null],
            ['WR-00005', 'SO-00005', 'Wireless Earbuds', '2026-07-27', '2027-07-27', 'Expired', 'Warranty period ended'],
        ];

        foreach ($records as [$number, $order, $product, $start, $end, $status, $reason]) {
            $this->firstId('warranty_records', 'warranty_id', ['warranty_number' => $number], [
                'order_id' => $this->orderId($order),
                'product_id' => $this->productId($product),
                'warranty_start' => $start,
                'warranty_end' => $end,
                'warranty_status' => $status,
                'archive_reason' => $reason,
                'created_at' => '2026-07-28 00:31:20',
                'updated_at' => '2026-07-28 00:31:20',
            ]);
        }
    }

    private function firstId(string $table, string $primaryKey, array $identity, array $values): int
    {
        $existing = DB::table($table)->where($identity)->value($primaryKey);
        if ($existing) {
            return (int) $existing;
        }

        return (int) DB::table($table)->insertGetId(array_merge($identity, $values), $primaryKey);
    }

    private function employeeId(string $username): int
    {
        return (int) DB::table('employees')->where('username', $username)->value('employee_id');
    }

    private function customerId(string $email): int
    {
        return (int) DB::table('customers')->where('email', $email)->value('customer_id');
    }

    private function productId(string $name): int
    {
        return (int) DB::table('products')->where('product_name', $name)->value('product_id');
    }

    private function pricingRuleId(string $name): int
    {
        return (int) DB::table('pricing_rules')->where('rule_name', $name)->value('pricing_rule_id');
    }

    private function quotationId(string $number): int
    {
        return (int) DB::table('quotations')->where('quotation_number', $number)->value('quotation_id');
    }

    private function orderId(string $number): int
    {
        return (int) DB::table('sales_orders')->where('order_number', $number)->value('order_id');
    }

    private function orderItemId(string $orderNumber, string $productName, int $quantity, float $price): int
    {
        return $this->firstId('sales_order_items', 'order_item_id', [
            'order_id' => $this->orderId($orderNumber),
            'product_id' => $this->productId($productName),
        ], [
            'quantity' => $quantity,
            'unit_price' => $price,
            'discount' => 0,
            'subtotal' => $price * $quantity,
        ]);
    }
}

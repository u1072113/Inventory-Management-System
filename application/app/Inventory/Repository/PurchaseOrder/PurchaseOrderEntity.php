<?php namespace Inventory\Repository\PurchaseOrder;

use App\Company;
use App\Helper;
use App\Product;
use App\PurchaseOrder;
use App\PurchaseOrderList;
use App\PurchaseRequest;
use App\PurchaseRequestItem;
use App\Restock;
use App\Supplier;
use Auth;
use Carbon;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\Setting\SettingsInterface;
use DB;
use Excel;
use File;
use Mail;
use Schema;

/**
 * Class PurchaseOrderEntity
 * @package Inventory\Repository\PurchaseOrder
 */
class PurchaseOrderEntity implements PurchaseOrderInterface
{

    public function __construct(ProductInterface $product, SettingsInterface $setting)
    {
        $this->product = $product;
        $this->setting = $setting;
    }

    /**
     * Add a purchase Order
     * @param $purchaseOrder
     * @return mixed
     * @internal param $supplierId
     * @internal param array $order
     * @internal param int $isFavourite
     * @internal param int $isEmail
     */
    public function addPurchaseOrder($purchaseOrder)
    {
        $supplier = Supplier::find($purchaseOrder['supplierName']);
        if (isset($purchaseOrder['isFavourite'])) {
            $favourite = 1;
        } else {
            $favourite = 0;
        }
        $order = PurchaseOrderList::where('created_at', '>=', Carbon::now()->startOfMonth())->withTrashed()->get();
        $lpoNumber = sprintf("%03d", $order->count() + 1);
        $date = Carbon::today()->format('ym');
        $lpo = 'PO' . $date . $lpoNumber;
        $purchaseList = array(
            "polSupplierId" => $purchaseOrder['supplierName'],
            "polSupplierName" => $supplier->supplierName,
            "polDeliverBy" => $purchaseOrder["deliverBy"],
            "polTermsOfPayment" => $purchaseOrder["termsOfPayment"],
            "isFavourite" => $favourite,
            "remarks" => $purchaseOrder["remarks"],
            "departmentId" => $purchaseOrder["departmentId"],
            'lpoNumber' => $lpo,
            'lpoStatus' => 'Awaiting Approval',
            'lpoCurrencyType' => $purchaseOrder["lpoCurrencyType"],
            'lpoDate' => $purchaseOrder["lpoDate"],
            'prRequestNo' => $purchaseOrder["prRequestNo"],
            'vatTaxAmount' => $purchaseOrder['vatTaxAmount']
        );
        $purchaseList = PurchaseOrderList::create($purchaseList);
        $id = $purchaseList->id;
        $prRequestNo = $purchaseList->prRequestNo;
        $orders = json_decode($purchaseOrder['order']);
        $orderList = array();
        foreach ($orders as $order) {
            PurchaseOrder::create(array(
                'plId' => $purchaseList->id,
                'poItemCode' => $order->id,
                'poDescription' => $order->productName,
                'poQty' => $order->reorder,
                "taxable" => $order->taxable,
                'poUnitPrice' => $order->unitCost
            ));
        }

        $status = array(
            'lpoCreatedOn' => Carbon::now(),
            'lpoCreated' => 1,
        );
        $this->updateRequestLpoCreated($prRequestNo, $status);
        call_user_func(array($this, env('LPOFORMAT', 'generatePdf')), $id);
      //  $this->generateGenericPdf($id);

        if (array_key_exists('isEmail', $purchaseOrder)) {
            $this->sendEmail($purchaseOrder['supplierName'], $purchaseList->id);
        }
    }

    /**
     * Updates Purchase Request Status if any
     * @param $prRequestNo
     * @param array $status
     */
    public function updateRequestLpoCreated($prRequestNo, array $status)
    {

        PurchaseRequest::where('requestNo', $prRequestNo)->update($status);
    }

    /**
     * Update Existing Purchase Order
     * @param $purchaseId
     * @param $purchaseOrder
     */
    public function updatePurchaseOrder($purchaseId, $purchaseOrder)
    {
        $supplier = Supplier::find($purchaseOrder['supplierName']);
        if (isset($purchaseOrder['isFavourite'])) {
            $favourite = 1;
        } else {
            $favourite = 0;
        }
        $purchaseList = array(
            "polSupplierId" => $purchaseOrder['supplierName'],
            "polSupplierName" => $supplier->supplierName,
            "polDeliverBy" => $purchaseOrder["deliverBy"],
            "polTermsOfPayment" => $purchaseOrder["termsOfPayment"],
            "isFavourite" => $favourite,
            "remarks" => $purchaseOrder["remarks"],
            "departmentId" => $purchaseOrder["departmentId"],
            'lpoStatus' => $purchaseOrder["lpoStatus"],
            'rejectionReason' => $purchaseOrder["rejectionReason"],
            'lpoCurrencyType' => $purchaseOrder["lpoCurrencyType"],
            'lpoDate' => $purchaseOrder["lpoDate"]
        );
        if ($purchaseOrder["lpoStatus"] == "approved") {
            $status = array(
                'lpoApprovedOn' => Carbon::now(),
                'lpoApproved' => 1,
            );
            $this->updateRequestLpoCreated($purchaseOrder["prRequestNo"], $status);
        }
        PurchaseOrderList::find($purchaseId)->update($purchaseList);
        $orders = json_decode($purchaseOrder['order']);
        PurchaseOrder::wherePlid($purchaseId)->delete();
        foreach ($orders as $order) {
            PurchaseOrder::create(array(
                'plId' => $purchaseId,
                'poItemCode' => $order->id,
                'poDescription' => $order->productName,
                'poQty' => $order->reorder,
                'poUnitPrice' => $order->unitCost,
                "taxable" => $order->taxable,
            ));
        }
        call_user_func(array($this, env('LPOFORMAT', 'generatePdf')), $purchaseId);
        // $this->generateGenericPdf($purchaseId);
    }

    public function generateGenericPdf($purchaseOrderId)
    {
        $product = $this->getPurchaseOrder($purchaseOrderId);
        $allOrders = PurchaseOrder::where('plId', $purchaseOrderId)->get();
        $pages = count($allOrders->chunk(19));
        $path = storage_path() . '/purchaseOrder.xlsx';
        $file = $this->createGenericFile($product, $allOrders, $pages, $path);
        $path = Helper::downloadPathWithFolder('lpos') . '/' . $product->lpoNumber . '-' . str_slug($product->supplier->supplierName) . '.xlsx';
        $path = Helper::downloadPathWithFolder('lpos') . '/' . $product->lpoNumber . '-' . str_slug($product->supplier->supplierName) . '.xlsx';
        $pdf = Helper::downloadPathWithFolder('lpos') . '/' . $product->lpoNumber . '-' . str_slug($product->supplier->supplierName) . '.pdf';
        File::delete($path);
        File::delete($pdf);
        File::move($file['full'], $path);
        if (env('GENERATEPDFS', true) == true) {
            if (env('OS') == 'Windows_NT') {
                $outdir = Helper::downloadPath() . '/lpos';
                $cmd = env('SOFFICE') . ' --outdir "' . $outdir . '"  "' . $path . '"';
                exec($cmd);
            } else {
                $outdir = Helper::downloadPath() . '/lpos';
                $cmd = 'export HOME=/tmp &&  ' . env('SOFFICE') . ' --outdir ' . $outdir . " '" . $path . "'";
                shell_exec($cmd);
            }
        }
    }

    /**
     * Generate PDF
     * @param $purchaseOrderId
     */
    public function generatePdf($purchaseOrderId)
    {

        $product = $this->getPurchaseOrder($purchaseOrderId);
        $allOrders = PurchaseOrder::where('plId', $purchaseOrderId)->get();


        $pages = count($allOrders->chunk(10));

        if ($product->companyId == 1) {
            if ($pages == 1) {
                $path = storage_path() . '/TCCLPOLAYOUT.xlsx';
            } else if ($pages == 2) {
                $path = storage_path() . '/TCCLPOLAYOUT2.xlsx';
            } else if ($pages == 3) {
                $path = storage_path() . '/TCCLPOLAYOUT3.xlsx';
            } else {
                $path = storage_path() . '/TCCLPOLAYOUT.xlsx';
            }
            $file = $this->createTccFile($product, $allOrders, $pages, $path);
        } else {
            if ($pages == 1) {
                $path = storage_path() . '/LPOLAYOUT.xlsx';
            } else if ($pages == 2) {
                $path = storage_path() . '/LPOLAYOUT2.xlsx';
            } else if ($pages == 3) {
                $path = storage_path() . '/LPOLAYOUT3.xlsx';
            } else {
                $path = storage_path() . '/LPOLAYOUT.xlsx';
            }
            $file = $this->createAccelerFile($product, $allOrders, $pages, $path);
        }
        $path = Helper::downloadPathWithFolder('lpos') . '/' . $product->lpoNumber . '-' . str_slug($product->supplier->supplierName) . '.xlsx';
        File::delete($path);
        File::move($file['full'], $path);
        /*
        $path = Helper::downloadPathWithFolder('lpos') . '/' . $product->lpoNumber . '-' . str_slug($product->supplier->supplierName) . '.xlsx';
        $pdf = Helper::downloadPathWithFolder('lpos') . '/' . $product->lpoNumber . '-' . str_slug($product->supplier->supplierName) . '.pdf';
        File::delete($path);
        File::delete($pdf);
        File::move($file['full'], $path);

        if (env('OS') == 'Windows_NT') {
            $outdir = Helper::downloadPath() . '/lpos';
            $cmd = env('SOFFICE') . ' --outdir "' . $outdir . '"  "' . $path . '"';
            exec($cmd);
        } else {
            $outdir = Helper::downloadPath() . '/lpos';
            $cmd = 'export HOME=/tmp &&  ' . env('SOFFICE') . ' --outdir ' . $outdir . " '" . $path . "'";
            shell_exec($cmd);
        }
        */
    }

    public function createGenericFile($product, $allOrders, $pages, $path)
    {
        $company = Company::find(Auth::user()->companyId);
        $file = Excel::load($path, function ($reader) use ($product, $allOrders, $pages, $company) {
            $lpoCount = 1;
            foreach ($allOrders->chunk(10) as $orders) {
                $page = 'LPO-Page' . $lpoCount;
                $reader->sheet($page, function ($sheet) use ($product, $orders, $lpoCount, $pages, $company) {
                    //Company Name
                    $sheet->cell('A3', function ($cell) use ($company) {

                        $cell->setValue($company->companyName);

                    });
                    //Company Slogan
                    $sheet->cell('A4', function ($cell) use ($company) {

                        $cell->setValue($company->companySlogan);

                    });

                    //Ship to
                    $sheet->cell('E8', function ($cell) use ($company) {

                        $cell->setValue($company->companyName);

                    });
                    $sheet->cell('E9', function ($cell) use ($company) {

                        $cell->setValue($company->street);

                    });
                    $sheet->cell('E10', function ($cell) use ($company) {

                        $cell->setValue($company->city);

                    });
                    $sheet->cell('E11', function ($cell) use ($company) {

                        $cell->setValue($company->phone);

                    });

                    //Date of LPO
                    $sheet->cell('G3', function ($cell) use ($product) {

                        $cell->setValue($product->lpoDate);

                    });
                    //Supplier Details
                    $sheet->cell('B8', function ($cell) use ($product) {

                        $cell->setValue($product->supplier->supplierName);

                    });

                    $sheet->cell('B9', function ($cell) use ($product) {

                        $cell->setValue($product->supplier->address);

                    });

                    $sheet->cell('G38', function ($cell) use ($product) {

                        $cell->setValue($product->vatTaxAmount);

                    });

                    //LPO Details
                    $sheet->cell('G4', function ($cell) use ($product, $lpoCount, $pages) {
                        if ($pages > 1) {
                            $cell->setValue($product->lpoNumber . '/' . $lpoCount);
                        } else {
                            $cell->setValue($product->lpoNumber);
                        }
                    });

                    $sheet->cell('G5', function ($cell) use ($product) {

                        $cell->setValue($product->lpoCurrencyType);

                    });

                    //Summarry Details
                    $sheet->cell('F15', function ($cell) use ($product) {

                        $cell->setValue($product->polDeliverBy);

                    });
                    $sheet->cell('C15', function ($cell) use ($product) {

                        $cell->setValue($product->polTermsOfPayment);

                    });
                    $sheet->cell('AO246', function ($cell) use ($product) {

                        $cell->setValue($product->department->name);

                    });

                    $sheet->cell('A274', function ($cell) use ($product) {

                        $cell->setValue($product->remarks);

                    });

                    $rowNumbers = [18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36];
                    $count = 0;
                    foreach ($orders as $order) {
                        //Set Description
                        $sheet->cell('B' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue(trim($order->poDescription));

                        });
                        //Set Quantity
                        $sheet->cell('A' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->poQty);

                        });
                        //Set Unit Price
                        $sheet->cell('F' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->poUnitPrice);

                        });
                        //set Tax
                        $sheet->cell('E' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->taxable);

                        });

                        $count = $count + 1;

                    }

                });
                $lpoCount = $lpoCount + 1;
            }

        })->store('xlsx');
        return $file;
    }

    public function createAccelerFile($product, $allOrders, $pages, $path)
    {
        $file = Excel::load($path, function ($reader) use ($product, $allOrders, $pages) {
            $lpoCount = 1;
            foreach ($allOrders->chunk(10) as $orders) {
                $page = 'LPO-Page' . $lpoCount;
                $reader->sheet($page, function ($sheet) use ($product, $orders, $lpoCount, $pages) {

                    //Date of LPO
                    $sheet->cell('I13', function ($cell) use ($product) {

                        $cell->setValue($product->lpoDate);

                    });
                    //Supplier Details
                    $sheet->cell('I23', function ($cell) use ($product) {

                        $cell->setValue($product->supplier->supplierName);

                    });

                    $sheet->cell('I31', function ($cell) use ($product) {

                        $cell->setValue($product->supplier->address);

                    });

                    //LPO Details
                    $sheet->cell('ID32', function ($cell) use ($product, $lpoCount, $pages) {
                        if ($pages > 1) {
                            $cell->setValue($product->lpoNumber . '/' . $lpoCount);
                        } else {
                            $cell->setValue($product->lpoNumber);
                        }
                    });

                    $sheet->cell('HB45', function ($cell) use ($product) {

                        $cell->setValue($product->lpoCurrencyType);

                    });

                    //Summarry Details
                    $sheet->cell('AO222', function ($cell) use ($product) {

                        $cell->setValue($product->polDeliverBy);

                    });
                    $sheet->cell('AO234', function ($cell) use ($product) {

                        $cell->setValue($product->polTermsOfPayment);

                    });
                    $sheet->cell('AO246', function ($cell) use ($product) {

                        $cell->setValue($product->department->name);

                    });

                    $sheet->cell('A274', function ($cell) use ($product) {

                        $cell->setValue($product->remarks);

                    });

                    $rowNumbers = ['75', 88, 101, 114, 127, 140, 155, 168, 183, 196];
                    $count = 0;
                    foreach ($orders as $order) {
                        //Set Description
                        $sheet->cell('A' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue(trim($order->poDescription));

                        });
                        //Set Quantity
                        $sheet->cell('EW' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->poQty);

                        });
                        //Set Unit Price
                        $sheet->cell('GA' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->poUnitPrice);

                        });
                        //set Tax
                        $sheet->cell('HG' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->taxable);

                        });

                        $count = $count + 1;

                    }

                });
                $lpoCount = $lpoCount + 1;
            }

        })->store('xlsx');
        return $file;
    }

    public function createTccFile($product, $allOrders, $pages, $path)
    {
        $file = Excel::load($path, function ($reader) use ($product, $allOrders, $pages) {
            $lpoCount = 1;
            foreach ($allOrders->chunk(10) as $orders) {
                $page = 'LPO-Page' . $lpoCount;
                $reader->sheet($page, function ($sheet) use ($product, $orders, $lpoCount, $pages) {

                    //Date of LPO
                    $sheet->cell('I34', function ($cell) use ($product) {

                        $cell->setValue($product->lpoDate);

                    });
                    //Supplier Details
                    $sheet->cell('I44', function ($cell) use ($product) {

                        $cell->setValue($product->supplier->supplierName);

                    });

                    $sheet->cell('I52', function ($cell) use ($product) {

                        $cell->setValue($product->supplier->address);

                    });

                    //LPO Details
                    $sheet->cell('ID53', function ($cell) use ($product, $lpoCount, $pages) {
                        if ($pages > 1) {
                            $cell->setValue($product->lpoNumber . '/' . $lpoCount);
                        } else {
                            $cell->setValue($product->lpoNumber);
                        }
                    });

                    $sheet->cell('HB66', function ($cell) use ($product) {

                        $cell->setValue($product->lpoCurrencyType);

                    });

                    //Summarry Details
                    $sheet->cell('AO243', function ($cell) use ($product) {

                        $cell->setValue($product->polDeliverBy);

                    });
                    $sheet->cell('AO255', function ($cell) use ($product) {

                        $cell->setValue($product->polTermsOfPayment);

                    });
                    $sheet->cell('AO267', function ($cell) use ($product) {

                        $cell->setValue($product->department->name);

                    });

                    $sheet->cell('A295', function ($cell) use ($product) {

                        $cell->setValue($product->remarks);

                    });

                    $rowNumbers = ['96', 109, 122, 135, 148, 161, 176, 189, 204, 217];
                    $count = 0;
                    foreach ($orders as $order) {
                        //Set Description
                        $sheet->cell('A' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue(trim($order->poDescription));

                        });
                        //Set Quantity
                        $sheet->cell('EW' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->poQty);

                        });
                        //Set Unit Price
                        $sheet->cell('GA' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->poUnitPrice);

                        });
                        //set Tax
                        $sheet->cell('HG' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->taxable);

                        });

                        $count = $count + 1;

                    }

                });
                $lpoCount = $lpoCount + 1;
            }

        })->store('xlsx');
        return $file;
    }

    /**
     * Send Email with Purchase Order
     * @param $supplierId
     * @param $orderId
     */
    public function sendEmail($supplierId, $orderId)
    {
        $supplier = Supplier::find($supplierId);
        $order = PurchaseOrderList::with('orders')
            ->with('supplier')
            ->whereId($orderId)
            ->first();
        $email = $supplier->email;
        Mail::send(
            'emails.signatories',
            array(
                'order' => $order,
                'supplier' => $supplier
            ),
            function ($message) use ($email) {
                $message->to($email)->subject('Hi I could not get the status for the following printers');
            });

    }

    /**
     * Update delivery of received items
     * @param $purchaseOrderId
     */
    public function updateDelivery($purchaseOrderId)
    {

        PurchaseOrderList::find($purchaseOrderId)->update($this->getDeliveryCount($purchaseOrderId));
    }

    /**
     * Get Delivery Count of purchase order
     * @param $orderId
     * @return array
     */
    public function getDeliveryCount($orderId)
    {
        $order = PurchaseOrder::wherePlid($orderId);
        $total = $order->sum('poQty');
        $delivered = $order->sum('delivered');
        if ($delivered == 0) {
            return array('fullDelivery' => 0);
        } elseif ($delivered == $total) {
            return array('fullDelivery' => 1, 'partDelivery' => 0);
        } elseif ($delivered < $total) {
            return array('partDelivery' => 1);
        }
    }

    /**
     * Get list of purchase orders
     * @param array $params
     * @return mixed
     */
    public function getPurchaseOrders(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return PurchaseOrderList::with('orders')->with('supplier')->orderBy(
                $params['sort']['sortBy'],
                $params['sort']['direction']
            )
                ->paginate(
                    $pagination
                )->setPath('');
        }
        $purchaseOrder = new PurchaseOrderList();
        //Search
        if ($params['search']['search']) {
            $purchaseOrder = $this->search($purchaseOrder, $params['search']['search'], 'purchase_orders_list');
        }
        if (isset($params['status'])) {
            $purchaseOrder = $purchaseOrder->$params['status']();
        }

        //Check if user wants deleted items
        if (isset($params['deleted'])) {
            $purchaseOrder = $purchaseOrder->onlyTrashed();
        }

        //Search
        if ($params['search']['search']) {
            $purchaseOrder = $this->search($purchaseOrder, $params['search']['search'], 'purchase_orders_list');
        }
        return $purchaseOrder
            ->with('orders')
            ->with('supplier')
            ->orderBy('created_at', 'DESC')
            ->paginate($pagination)->setPath('');
    }


    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     * Used to search for products
     */
    public function search($item, $search, $table)
    {
        $columns = Schema::getColumnListing($table);
        unset($columns[0]);
        $first = $columns[1];
        $item = $item->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    /**
     * Get Purchase Order
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getPurchaseOrder($purchaseOrderId)
    {
        return PurchaseOrderList::with('orders')
            ->with('supplier')
            ->with('department')
            ->whereId($purchaseOrderId)
            ->first();
    }

    /**
     * Deletes Purchase Order
     * @param $purchaseOrderId
     */
    public function deletePurchaseOrder($purchaseOrderId)
    {
        PurchaseOrderList::destroy($purchaseOrderId);
        //PurchaseOrder::wherePlid($purchaseOrderId)->delete();
    }

    /**
     * Get number of purchase orders
     * @return int
     */
    public function getPurchaseOrderCount()
    {
        return PurchaseOrderList::all()->count();
    }

    /**
     * Suggest Purchase Order Items
     * @return mixed
     */
    public function suggestPurchaseOrder()
    {
        return Product::select(DB::raw('id, productName, IFNULL(unitCost,1) as unitCost, reorderAmount - amount as reorder, reorderAmount, amount'))
            ->whereRaw('amount < reorderAmount and amount > 0')->limit(env('PURCHASE_ORDER_LIMIT'))->get();
    }

    /**
     * Gets all products for use in a select
     * @return mixed
     */
    public function autoSuggestList()
    {
        return Product::select(
            DB::raw(
                'concat(productName," - ",IFNULL(reorderAmount,0) - IFNULL(amount,0),"") as cow,id'
            )
        )
            ->lists('cow', 'id');
    }

    /**
     * Get one product with restock suggestion
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getProductWithRestockSuggestion($purchaseOrderId)
    {
        return Product::select(
            DB::raw(
                'productName,
                 IFNULL(amount,0) as amount,
                 IFNULL(abs(reorderAmount - amount),1) as reorder,
                 reorderAmount,
                 IFNULL((case when unitCost < 1 Then 1 end),1) as unitCost,
                  id
                  '
            )
        )
            ->whereId($purchaseOrderId)
            ->first();
    }

    /**
     * Restock from purchase Order
     * @param $product
     */
    public function restockFromPurchaseOrder($product)
    {

        $restock = array(
            'productID' => $product->id,
            'unitCost' => $product->unitCost,
            'amount' => $product->received,
            'supplierID' => $product->supplierId,
            'receivedBy' => ''
        );
        $reorder = array(
            'fullDelivery' => $product->fullDelivery,
            'partDelivery' => $product->partDelivery,
        );
        $purchaseOrder = PurchaseOrder::find($product->orderId);
        $purchaseOrder->increment('delivered', $product->received);
        PurchaseOrder::find($product->orderId)->update($reorder);
        Restock::create($restock);

        if ($product->id) {
            $this->product->increaseProduct($product->received, $product->id);
        }
    }

    /**
     * Generate LPOs PDF
     * @return mixed
     */
    public function lpoReport()
    {
        return PurchaseOrderList::all();
    }

    /**
     * Restore a deleted item
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        PurchaseOrderList::withTrashed()->where('id', $id)->restore();
    }

    /**
     * Get Order From Request
     * @param $id
     * @return mixed
     */
    public function getFromRequest($id)
    {

        $products = PurchaseRequestItem::select('prItemCode')->wherePurchaserequestid($id)->get();
        $items = array_flatten($products->toArray());
        return DB::table('products')
            ->join('purchase_request_items', 'products.id', '=', 'purchase_request_items.prItemCode')
            ->whereIn('products.id', $items)
            ->select(DB::raw(
                'productName,
                 IFNULL(amount,0) as amount,
                 IFNULL(abs(reorderAmount - amount),1) as reorder,
                 reorderAmount,
                 IFNULL((case when unitCost < 1 Then 1 end),1) as unitCost,
                  products.id
                  '
            ))
            ->get();
    }


}

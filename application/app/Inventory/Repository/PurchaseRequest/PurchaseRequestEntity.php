<?php namespace Inventory\Repository\PurchaseRequest;

use App\PurchaseRequest;
use App\PurchaseRequestItem;
use App\Product;
use Inventory\Repository\Setting\SettingsInterface;
use DB;
use Carbon\Carbon;
use Auth;
use Excel;
use App\Helper;
use File;

class PurchaseRequestEntity implements PurchaseRequestInterface
{
    public function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }
    /**
     * Add a purchase Order
     * @param $purchaseRequest
     * @return mixed
     * @internal param $purchaseOrder
     * @internal param $supplierId
     * @internal param array $order
     * @internal param int $isFavourite
     * @internal param int $isEmail
     */
    public function addPurchaseRequest($purchaseRequest)
    {
        $order = PurchaseRequest::where('created_at', '>=', Carbon::now()->startOfMonth())->withTrashed()->get();
        $purchaseRequestNumber = sprintf("%03d", $order->count() + 1);
        $date = Carbon::today()->format('ym');
        $purchaseRequestNumber = 'PR' . $date . $purchaseRequestNumber;

        if (array_key_exists('notifyOnLpoCreation', $purchaseRequest)) {
            $notify = implode(",", $purchaseRequest['notifyOnLpoCreation']);
        } else {
            $notify = null;
        }
        $requestDetails = array(
            "requesterName" => 1,
            "requestNo" => $purchaseRequestNumber,
            "owner" => $purchaseRequest['owner'],
            "remindMeOn" => $purchaseRequest['remindMeOn'],
            "departmentId" => $purchaseRequest["departmentId"],
            "notifyOnLpoCreation" => $notify
        );
        $purchaseList = PurchaseRequest::create($requestDetails);
        $id = $purchaseList->id;
        $requests = json_decode($purchaseRequest['request']);
        foreach ($requests as $request) {
            PurchaseRequestItem::create(array(
                'purchaserequestId' => $purchaseList->id,
                'prItemCode' => $request->id,
                'prDescription' => $request->prDescription,
                'prQty' => $request->prQty,
                "prPurpose" => $request->prPurpose,
            ));
        }

        $this->generatePdf($id);
    }

    /**
     * Update Existing Purchase Order
     * @param $purchaseRequestId
     * @param $purchaseRequest
     * @return
     * @internal param $purchaseId
     * @internal param $purchaseOrder
     */
    public function updatePurchaseOrder($purchaseRequestId, $purchaseRequest)
    {
        $requestDetails = array(
            "requesterName" => 1,
            "owner" => $purchaseRequest['owner'],
            "remindMeOn" => $purchaseRequest['remindMeOn'],
            "departmentId" => $purchaseRequest["departmentId"],
            "notifyOnLpoCreation" => implode(",", $purchaseRequest['notifyOnLpoCreation'])
        );
        $purchaseList = PurchaseRequest::find($purchaseRequestId)->update($requestDetails);
        $id = $purchaseRequestId;
        //Delete Previous Request Items
        PurchaseRequestItem::wherePurchaserequestid($purchaseRequestId)->delete();

        $requests = json_decode($purchaseRequest['request']);
        foreach ($requests as $request) {
            PurchaseRequestItem::create(array(
                'purchaserequestId' => $purchaseRequestId,
                'prItemCode' => $request->id,
                'prDescription' => $request->prDescription,
                'prQty' => $request->prQty,
                "prPurpose" => $request->prPurpose,
            ));
        }

        $this->generatePdf($id);
    }

    /**
     * Generate PDF
     * @param $purchaseRequestId
     * @return
     * @internal param $purchaseOrderId
     */
    public function generatePdf($purchaseRequestId)
    {
        $product = $this->getPurchaseRequest($purchaseRequestId);

        $allRequests = PurchaseRequestItem::where('purchaserequestId', $purchaseRequestId)->get();


        $pages = count($allRequests->chunk(10));

        $path = storage_path() . '/REQUISITION_LAYOUT.xlsx';

        $file = Excel::load($path, function ($reader) use ($product, $allRequests, $pages) {
            $lpoCount = 1;
            foreach ($allRequests->chunk(10) as $orders) {
                $page = 'Requisition';
                $reader->sheet($page, function ($sheet) use ($product, $orders, $lpoCount, $pages) {

                    $rowNumbers = ['16', 17, 18, 19, 20, 21, 22, 23, 24, 25];
                    $count = 0;
                    foreach ($orders as $order) {
                        //Set Description
                        $sheet->cell('B' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue(trim($order->prDescription));

                        });
                        //Set Quantity
                        $sheet->cell('C' . $rowNumbers[$count], function ($cell) use ($order) {


                            $cell->setValue($order->prPurpose);

                        });
                        //Set Unit Price
                        $sheet->cell('D' . $rowNumbers[$count], function ($cell) use ($order) {

                            $cell->setValue($order->prQty);

                        });

                        $count = $count + 1;

                    }

                });
                $lpoCount = $lpoCount + 1;
            }

        })->save('xlsx');

        $path = Helper::downloadPathWithFolder('purchaserequests') . '/' . $product->requestNo . '-' . str_slug($product->requestOwner->name) . '.xlsx';
        File::delete($path);
        File::move($file['full'], $path);

        $path = Helper::downloadPathWithFolder('purchaserequests') . '/' . $product->requestNo . '-' . str_slug($product->requestOwner->name) . '.xlsx';
        $pdf = Helper::downloadPathWithFolder('purchaserequests') . '/' . $product->requestNo . '-' . str_slug($product->requestOwner->name) . '.pdf';
        File::delete($pdf);


        if (env('OS') == 'Windows_NT') {
            $outdir = Helper::downloadPath() . '/purchaserequests';
            $cmd = env('SOFFICE') . ' --outdir "' . $outdir . '"  "' . $path . '"';
            exec($cmd);
        } else {
            $outdir = Helper::downloadPath() . '/purchaserequests';
            $cmd = 'export HOME=/tmp &&  ' . env('SOFFICE') . ' --outdir ' . $outdir . " '" . $path . "'";
            shell_exec($cmd);
        }

    }

    /**
     * Send Email with Purchase Order
     * @param $supplierId
     * @param $orderId
     */
    public function sendEmail($supplierId, $orderId)
    {
        // TODO: Implement sendEmail() method.
    }

    /**
     * Get list of purchase orders
     * @param array $params
     * @return mixed
     */
    public function getPurchaseRequests(array $params)
    {
        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return PurchaseRequest::with('requestOwner')->with('requestedBy')->orderBy(
                $params['sort']['sortBy'],
                $params['sort']['direction']
            )
                ->paginate(
                    env('RECORDS_VIEW')
                )->setPath('');
        }
        $purchaseRequest = new PurchaseRequest();
        //Search
        if ($params['search']['search']) {
            $purchaseRequest = $this->search($purchaseRequest, $params['search']['search'], 'purchase_request_items');
        }
        if (isset($params['status'])) {
            $purchaseRequest = $purchaseRequest->$params['status']();
        }

        //Check if user wants deleted items
        if (isset($params['deleted'])) {
            $purchaseRequest = $purchaseRequest->onlyTrashed();
        }
        return $purchaseRequest
            ->with('requestOwner')
            ->with('requestedBy')
            ->orderBy('created_at', 'DESC')
            ->paginate(env('RECORDS_VIEW'))->setPath('');
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
        // TODO: Implement search() method.
    }

    /**
     * Get Purchase Order
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getPurchaseRequest($purchaseOrderId)
    {
        return PurchaseRequest::with('requestOwner')
            ->with('requestedBy')
            ->find($purchaseOrderId);
    }

    /**
     * Deletes Purchase Order
     * @param $purchaseOrderId
     */
    public function deletePurchaseRequest($purchaseOrderId)
    {
        return PurchaseRequest::destroy($purchaseOrderId);
    }

    /**
     * Get number of purchase orders
     * @return int
     */
    public function getPurchaseRequestCount()
    {
        return PurchaseRequest::all()->count();
    }

    /**
     * Suggest Purchase Order Items
     * @return mixed
     */
    public function suggestPurchaseRequest()
    {
        return Product::select(DB::raw('id, productName as prDescription,  reorderAmount - amount as prQty,reorderAmount, "Write a descriptive Purpose for this request" as prPurpose'))
            ->whereRaw('amount < reorderAmount and amount > 0')->limit(env('PURCHASE_ORDER_LIMIT'))->get();
    }

    /**
     * Gets all products for use in a select
     * @return mixed
     */
    public function autoSuggestList()
    {
        // TODO: Implement autoSuggestList() method.
    }

    /**
     * Get one product with restock suggestion
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getProductForPurchaseRequest($purchaseOrderId)
    {
        return Product::select(
            DB::raw(
                'id, productName as prDescription, IFNULL(abs(reorderAmount - amount),1)  as prQty,reorderAmount, "Write a descriptive Purpose for this request" as prPurpose'
            )
        )
            ->whereId($purchaseOrderId)
            ->first();
    }

    /**
     * Restore a deleted item
     * @return mixed
     */
    public function restore($id)
    {
        PurchaseRequest::withTrashed()->where('id', $id)->restore();
    }


}
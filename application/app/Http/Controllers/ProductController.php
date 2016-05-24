<?php namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\ProductFormRequest;
use App\Product;
use Carbon;
use Inventory\Repository\Product\ProductInterface;
use DNS2D;
use Excel;
use Flash;
use Image;
use Input;
use Mail;
use PDF;
use Redirect;
use Request;
use Response;
use Schema;
use DNS1D;

class ProductController extends Controller
{
    /**
     * Constructor for Products
     * @param ProductInterface $product
     */
    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //Flash::message('d','danger');

        //  $data = DNS2D::getBarcodePNG("4445645656", "QRCODE", 100, 100);
        //  Helper::saveBarcode($data);
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $stockItems = $this->product->all(compact('sort', 'search'));
        $amountChart = $this->product->getProductChart();
        $message = "Products in Stock";
        return View('products/index')->with(compact('stockItems', 'message'))->with('amountChart', json_encode($amountChart));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $products = Product::select('productName')->lists('productName');
        $products = json_encode($products);
        $serials = Product::select('productSerial')->lists('productSerial');
        $serials = json_encode($serials);

        return View('products/newproduct')->with(compact('products', 'serials'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ProductFormRequest $validate)
    {
        $this->product->saveProduct(Input::all());

        return Redirect::action('ProductController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->getProductById($id);
        return view('products/view')->with(compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $products = Product::select('productName')->lists('productName');
        $products = json_encode($products);
        $serials = Product::select('productSerial')->lists('productSerial');
        $serials = json_encode($serials);
        $product = $this->product->getProductById($id);
        return View('products/newproduct')->with(compact('product', 'products', 'serials'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $this->product->updateProduct($id, Input::all());
        return Redirect::action('ProductController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->product->deleteProduct($id);
        return Response::json(['ok' => 'ok']);
    }

    /**
     * Uploads a new photo
     */
    public function uploadPhoto()
    {
        if (Input::hasFile('file')) {
            $product_image = Input::file('file');
            $destinationPath = Helper::downloadPath() . '/products/';
            $filename = str_random(6) . '_' . $product_image->getClientOriginalName();
            $save_path = $destinationPath . $filename;
            Image::make(Input::file('file'))->fit(200)->save($save_path);
            return array('save_path' => $filename);
        }

    }

    public function getOutOfStock()
    {
        $stockItems = $this->product->getOutOfStock();
        $message = "Items that are out of Stock";
        return View('products/index')->with(compact('stockItems', 'message'));
    }

    /**
     * Returns Deleted Stock items for restoration
     * @return $this
     */
    public function getDeleted()
    {
        $stockItems = $this->product->getDeletedProducts();
        $restore = 1;
        $message = "Deleted Products From Stock";
        return View('products/index')->with(compact('stockItems', 'message'))->with(compact('restore'));
    }

    public function getBelowLevels()
    {
        $stockItems = $this->product->getLowStock();
        $message = "Items that are below Stock Warning Levels";
        return View('products/index')->with(compact('stockItems', 'message'));
    }

    public function restore($id)
    {
        $this->product->restoreProduct($id);
        return Redirect::action('ProductController@index');
    }

    /**
     * Show Product Categories
     */
    public function showCategories()
    {
        $categories = $this->product->getProductCategories();
        return View('products/categories')->with(compact('categories'));
    }

    /**
     * Add Item to a category
     */
    public function addCategories()
    {
        $categories = $this->product->createProductCategory(Input::all());
        return array('id' => $categories->id, 'text' => $categories->categoryName);
    }

    /**
     * Save A Category
     */
    public function categoryGet()
    {
        $categories = $this->product->getProductCategoriesJson();
        return $categories;
    }

    public function categoryDelete($id)
    {
        $this->product->deleteProductCategory($id);
        return Response::json(['ok' => 'ok']);
    }

    public function getProducts()
    {
        return $this->product->getProductsAjax();
    }

    /**
     * Update A category
     */
    public function categoryUpdate()
    {

    }

    public function export()
    {
        $format = Input::query('type');
        $filename = Carbon::now()->format('Ymd_') . "ProductsList";
        $file = Excel::create($filename, function ($excel) {

            // Set the title
            $excel->setTitle('Products List and their Levels');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Products List and their Levels');
            $excel->sheet('Existing Products in Stock', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $sheet->fromModel($this->product->allReport());
            });

            $excel->sheet('Deleted Products in Stock', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $sheet->fromModel($this->product->getDeletedProductsReport());
            });

        });

        if ($format == "email") {
            $email = Input::get('email');
            $save_details = $file->store('xlsx');
            $content = "Please find attached a list of products and their levels";
            Mail::send('emails.master', array('content' => $content), function ($message) use ($save_details, $email) {
                $message->to($email)->subject('Products And Their Levels in Stock Control System');
                $message->attach($save_details['full']);
            });
            return Response::json(['ok' => 'ok']);
        } elseif ($format == "pdf") {
            $message = "";
            $stockItems = $this->product->allReport();
            $html = (string)view('products.report')->with(compact('message', 'stockItems'));
            $pdf = PDF::loadView('products.report', compact('message', 'stockItems'));
            return $pdf->stream();
            //   return PDF::download('invoice.pdf');
        } else {
            $file->download($format);
        }
    }

    public function import()
    {
        $columns = Schema::getColumnListing('products');

        if (Input::has('download')) {
            $this->dataTransfer();
        }

        if (Request::isMethod('post')) {
            $file = Input::file('workbenchfile');
            $this->uploadData($file);
            return Redirect::action('ProductController@index');
        }
        return View('products/import')->with(compact('columns'));
    }

    /**
     * Downloads Data Transfer Workbench File
     */
    public function dataTransfer()
    {
        $file = Excel::create("Data Transfer WorkBench", function ($excel) {

            // Set the title
            $excel->setTitle('Data Transfer WorkBench');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Data Transfer Workbench');
            $excel->sheet('Data Transfer Workbench', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $columns = Schema::getColumnListing('products');
                unset($columns[0]);
                unset($columns[15]);
                unset($columns[16]);
                unset($columns[17]);
                $sheet->fromArray($columns);
            });
        });
        $file->download();
    }

    public function uploadData($file)
    {
        Excel::load($file, function ($reader) {
            $results = $reader->toArray();
            foreach ($results as $result) {
                $this->product->saveProduct($result);
            }
        });
    }
}

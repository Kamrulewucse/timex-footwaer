<?php

namespace App\Http\Controllers;

use App\Model\ProductItem;
use App\Model\PurchaseInventory;
use App\Model\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class ProductItemController extends Controller
{
    public function index() {
        $productItems = ProductItem::all();
//        foreach ($productItems as $productItem){
//            $productItem->type = 1;
//            $productItem->save();
//        }
        return view('purchase.product_item.all', compact('productItems'));
    }

    public function add() {
        return view('purchase.product_item.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_items',
            'supplier' => 'required',
            'status' => 'required'
        ]);

        $productItem = new ProductItem();
        $productItem->name = $request->name;
        $productItem->supplier_id = $request->supplier;
        $productItem->unit_id = $request->unit;
        $productItem->type = 2;
        $productItem->description = $request->description;
        $productItem->status = $request->status;
        $productItem->save();

        return redirect()->route('product_item')->with('message', 'Product item add successfully.');
    }

    public function edit(ProductItem $productItem) {
        return view('purchase.product_item.edit', compact('productItem'));
    }

    public function editPost(ProductItem $productItem, Request $request) {
        //dd($productItem);
        $request->validate([
            'name' => ['required','string','max:255',Rule::unique('product_items')->ignore($productItem)],
            'supplier' => 'required',
            'status' => 'required'
        ]);
        //$image = '';
//        if ($request->image) {
//            // Upload Image
//            $file = $request->file('image');
//            $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
//            $destinationPath = 'public/uploads/product_image';
//            $file->move($destinationPath, $filename);
//
//            $image = 'uploads/product_image/' . $filename;
//        }
        //dd($productItem->image);

        $productItem->name = $request->name;
        $productItem->supplier_id = $request->supplier;
        $productItem->unit_id = $request->unit;
        $productItem->description = $request->description;
//        $productItem->image = $image;
        $productItem->status = $request->status;
        $productItem->save();

        return redirect()->route('product_item')->with('message', 'Product item edit successfully.');
    }
    public function productItemSuggestion(Request $request){
        if ($request->term){
            if (strlen($request->term)>2){
                $productItemIds = ProductItem::where('supplier_id',$request->company_id)->where('name', 'like',$request->term . '%')->pluck('id');
                $productsIds = PurchaseInventory::with(['productItem','productCategory','productColor','productSize', 'warehouse'])
//                ->where('serial', 'like', '%' . $request->term . '%')
//                ->where('quantity','>',0)
                    ->whereIn('product_item_id', $productItemIds)
                    ->where('quantity','>',0)
                    ->take(9)
                    ->get();
            }else{
                $productsIds = [];
            }

            return response()->json(['success'=>true,'productsIds'=>$productsIds]);
        }
    }
    public function productItemSuggestionByCompany(Request $request){
        if ($request->company_id){
            $productItemIds = ProductItem::where('supplier_id',$request->company_id)->pluck('id');
            $productsIds = PurchaseInventory::with(['productItem','productCategory','productColor','productSize', 'warehouse'])
//                ->where('serial', 'like', '%' . $request->term . '%')
//                ->where('quantity','>',0)
                ->whereIn('product_item_id', $productItemIds)
                ->where('quantity','>',0)
                ->take(10)
                ->get();

            return response()->json(['success'=>true,'productsIds'=>$productsIds]);
        }
    }
}

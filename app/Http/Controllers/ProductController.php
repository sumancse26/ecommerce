<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductDetail;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use App\Models\ProductWish;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function productDtlPage()
    {
        return view('pages.details-page');
    }
    public function wishListPage()
    {
        return view('pages.wish-list-page');
    }
    public function cartListPage()
    {
        return view('pages.cart-list-page');
    }
    public function productListByCategory(Request $req)
    {
        try {
            $productList = Product::where('category_id', $req->category_id)
                ->with('brand', 'category')
                ->get();
            return response()->json([
                'success' => true,
                'productList' => $productList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function productListByBrand(Request $req)
    {
        try {
            $productList = Product::where('brand_id', $req->brand_id)
                ->with('brand', 'category')
                ->get();
            return response()->json([
                'success' => true,
                'productList' => $productList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function productListBySlider()
    {
        try {
            $productList = ProductSlider::all();
            return response()->json([
                'success' => true,
                'productList' => $productList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function productListByRemarks(Request $req)
    {
        try {
            $productList = Product::where('remark', $req->remarks)
                ->with('brand', 'category')
                ->get();
            return response()->json([
                'success' => true,
                'productList' => $productList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function productDetailsById(Request $req)
    {
        try {
            $product = ProductDetail::where('product_id', $req->id)
                ->with('product.brand', 'product.category')
                ->first();

            return response()->json([
                'success' => true,
                'productDetails' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function reviewListByProduct(Request $req)
    {
        try {
            $reviewList = ProductReview::where('product_id', $req->product_id)
                ->with(['profile' => function ($query) {
                    $query->select('id', 'cus_name');
                }])
                ->get();
            return response()->json([
                'success' => true,
                'reviewList' => $reviewList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createUpdateReview(Request $req)
    {

        try {
            $userId = $req->header('userId');


            $customerInfo = CustomerProfile::where('user_id', $userId)->first();

            if ($customerInfo) {
                ProductReview::updateOrCreate(['customer_id' => $customerInfo->id, 'product_id' => $req->input('product_id')], $req->input());
            }
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createUpdateWish(Request $req)
    {
        try {
            $userId = $req->header('userId');
            $data = ProductWish::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $req->product_id],
                ['user_id' => $userId, 'product_id' => $req->product_id]
            );
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public  function getWishList(Request $req)
    {
        try {
            $userId = $req->header('userId');
            $data = ProductWish::where('user_id', $userId)
                ->with('product')
                ->get();
            return response()->json([
                'success' => true,
                'wishList' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteWishList(Request $req)
    {
        try {
            $data = ProductWish::where('user_id', $req->header('userId'))
                ->where('product_id', $req->product_id)
                ->delete();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createUpdateCart(Request $req)
    {
        try {
            $userId = $req->header('userId');
            $product = Product::where(['id' => $req->product_id])->first();

            $unitPrice = 0;
            if ($product->discount == 1) {
                $unitPrice = $product->discount;
            } else {
                $unitPrice = $product->price;
            };

            $totalPrice = $unitPrice * $req->input('qty');


            $data = ProductCart::updateOrCreate(
                ['product_id' => $req->input('product_id')],
                [
                    'user_id' => $userId,
                    'product_id' => $req->input('product_id'),
                    'color' => $req->input('color'),
                    'size' => $req->input('size'),
                    'qty' => $req->input('qty'),
                    'price' => $totalPrice,
                ]
            );
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCartList(Request $req)
    {
        try {
            $userId = $req->header('userId');
            $data = ProductCart::where('user_id', $userId)
                ->with('product')
                ->get();
            return response()->json([
                'success' => true,
                'cartList' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCartList(Request $req)
    {
        try {
            $data = ProductCart::where('user_id', $req->header('userId'))
                ->where('product_id', $req->product_id)
                ->delete();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

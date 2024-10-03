<?php

namespace App\Http\Controllers;

use App\Helper\SSLCommerz;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function createInvoice(Request $req)
    {
        DB::beginTransaction();
        try {
            $userId = $req->header('userId');
            $email = $req->header('userEmail');

            $tranId = uniqid();
            $deliveryStatus = "Pending";
            $paymentStatus = "Pending";

            $profile = CustomerProfile::where('user_id', $userId)->first();
            $cart = ProductCart::where('user_id', $userId)->get();

            $customerDtl = "$profile->cus_name $profile->cus_add, $profile->cus_city, $profile->cus_state, $profile->cus_postcode, $profile->cus_country, $profile->cus_phone, $profile->cus_fax";
            $shippingDtl = "$profile->ship_name $profile->ship_add, $profile->ship_city, $profile->ship_state, $profile->ship_postcode, $profile->ship_country, $profile->ship_phone";

            $total = ProductCart::where('user_id', $userId)->sum('price');
            $vat = ($total * 5) / 100;  //dynamic next time
            $payable = $total + $vat;

            $invoice = Invoice::create([
                'user_id' => $userId,
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'cus_details' => $customerDtl,
                'ship_details' => $shippingDtl,
                'tran_id' => $tranId,
                'delivery_status' => $deliveryStatus,
                'payment_status' => $paymentStatus
            ]);


            foreach ($cart as $item) {
                InvoiceProduct::create([
                    'user_id' => $userId,
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'sale_price' => $item->price,
                    'qty' => $item->qty

                ]);

                $item->delete();
            }

            $paymentMethods = SSLCommerz::InitiatePayment($profile, $payable, $tranId, $email);


            DB::commit();

            return response()->json(['success' => true, 'message' => 'Payment initiated', 'paymentMethods' => $paymentMethods, 'payable' => $payable, 'vat' => $vat, 'total' => $total], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getInvoice(Request $req)
    {
        try {
            $userId = $req->header('userId');
            $data = Invoice::where('user_id', $userId)
                ->with('invoiceProduct')
                ->get();
            return response()->json([
                'success' => true,
                'invoiceList' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getInvoiceProduct(Request $req)
    {

        try {
            $data = InvoiceProduct::where('invoice_id', $req->id)->with(['product' => function ($query) {
                $query->select('id', 'title');
            }])->get();
            return response()->json([
                'success' => true,
                'invoiceProducts' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function completeOrder(Request $req)
    {
        try {
            $data = Invoice::where('id', $req->invoice_id)->first();
            $profile = CustomerProfile::where('user_id', $data->user_id)->first();

            $payable = $data->payable;
            $tranId = $data->tran_id;
            $email = $req->header('userEmail');

            $paymentMethods = SSLCommerz::InitiatePayment($profile, $payable, $tranId, $email);

            return response()->json([
                'success' => true,
                'paymentMethods' => $paymentMethods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    function paymentSuccess(Request $request)
    {
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
    }
    public function apiPaymentSuccess(Request $request)
    {
        $invoice = SSLCommerz::InitiateSuccess($request->input('tran_id'));

        if ($invoice) {
            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'tran_id' => $invoice->tran_id,
                'invoice' => $invoice,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found or payment update failed',
            ], 404);
        }
    }


    function paymentCancel(Request $request)
    {
        SSLCommerz::InitiateCancel($request->query('tran_id'));
        return redirect('/profile');
    }
    function apiPaymentCancel(Request $request)
    {
        SSLCommerz::InitiateCancel($request->query('tran_id'));
        // return redirect('/profile');
    }

    function paymentFail(Request $request)
    {
        return SSLCommerz::InitiateFail($request->query('tran_id'));
        return redirect('/profile');
    }
    function apiPaymentFail(Request $request)
    {
        return SSLCommerz::InitiateFail($request->query('tran_id'));
        // return redirect('/profile');
    }

    function paymentIPN(Request $request)
    {
        Log::info($request->all());
        return SSLCommerz::InitiateIPN($request->input('tran_id'), $request->input('status'), $request->input('val_id'));
    }
}

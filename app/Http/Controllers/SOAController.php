<?php

namespace App\Http\Controllers;

use App\Jobs\SendSOAEmail;
use App\Models\Account;
use Illuminate\Http\Request;

class SOAController extends Controller
{
    /**
     * Display the SOA index page.
     */
    public function index()
    {
        $accounts = Account::with('customer')->paginate(20);
        $totalAccounts = Account::count();

        return view('soa.index', compact('accounts', 'totalAccounts'));
    }

    /**
     * Send SOA for a single account.
     */
    public function sendToAccount(Account $account)
    {
        dispatch(new SendSOAEmail($account));

        return redirect()->back()->with('success', 'SOA sent successfully to ' . $account->customer->first_name);
    }

    /**
     * Send SOA to multiple accounts with 5-second delay.
     */
    public function sendBatch(Request $request)
    {
        $accountIds = $request->input('account_ids', []);
        $delay = 0;

        foreach ($accountIds as $accountId) {
            $account = Account::find($accountId);
            if ($account) {
                dispatch(new SendSOAEmail($account))->delay(now()->addSeconds($delay));
                $delay += 5;
            }
        }

        return redirect()->back()->with('success', 'SOA batch send scheduled successfully!');
    }

    /**
     * Send SOA to all accounts with 5-second delay between each.
     */
    public function sendAll()
    {
        $accounts = Account::all();
        $delay = 0;

        foreach ($accounts as $account) {
            dispatch(new SendSOAEmail($account))->delay(now()->addSeconds($delay));
            $delay += 5;
        }

        return redirect()->back()->with('success', 'SOA sent to all accounts!');
    }
}

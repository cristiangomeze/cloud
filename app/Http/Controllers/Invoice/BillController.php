<?php

namespace App\Http\Controllers\Invoice;

use Inertia\Inertia;
use App\Organization;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Model\Invoice\{Invoice, Bill};
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\{Request, Redirect};

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $slug
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index($slug)
    {
        $this->authorize('view', Bill::class);

        return Inertia::render('Invoice/Bill/Index', [
            'organization' => $organization = Organization::whereSlug($slug)->firstOrFail(),
            'filters' => Request::all('search', 'status', 'bill_type'),
            'bills' => $organization->bills()
                ->with('client:id,name,last_name', 'payments:id,bill_id,paid_out')
                ->orderByDesc('created_at')
                ->filter(Request::only('search', 'status', 'bill_type'))
                ->paginate()
                ->transform(function ($item) {
                    return collect($item->toArray())->merge([
                        'created_at' => $item->created_at->format('F d, Y'),
                        'status' => ucwords(strtolower($item->status)),
                        'payments' => $item->dueAmount(),
                        'total' => $item->total(),
                        'expired_at' => optional($item->expired_at)->format('F d, Y'),
                    ]);
                })
                ->only('id', 'client', 'created_at', 'total', 'discount','payments', 'expired_at', 'status')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $slug
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($slug)
    {
        $this->authorize('create', Bill::class);

        return Inertia::render('Invoice/Bill/Create', [
            'organization' => $organization = Organization::whereSlug($slug)->firstOrFail(),
            'clients' => $organization->clients->map->only('id', 'name', 'last_name', 'id_card'),
            'articles' => $organization->articles->map->only('id', 'name', 'description', 'cost')->transform(function ($item) {
                return collect($item)->merge([
                    'name' => Str::limit($item['name'], '30', '...'),
                    'description' => Str::limit($item['description'], '40', '...')
                ]);
            }),
            'type_bill' => Bill::ALL_BILL_TYPE
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($slug)
    {
        $this->authorize('create', Bill::class);

        $organization = Organization::whereSlug($slug)->firstOrFail();

        $bill = Invoice::make(Request::instance())->create($organization);

        return  Redirect::route('invoice.bills.show', ['slug' => $slug, 'bill' => $bill])
            ->with('success', 'Factura creada correctamente.');
    }

    /**
     * @param $slug
     * @param \App\Model\Invoice\Bill $bill
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($slug, Bill $bill)
    {
        $this->authorize('view', $bill);

        $bill->load('client', 'articles');

        return Inertia::render('Invoice/Bill/Show', [
            'organization' => Organization::whereSlug($slug)->firstOrFail(),
            'bill' => [
                'id' => $bill->id,
                'client' => $bill->client ? "{$bill->client->name} {$bill->client->last_name}" : 'Cliente al contado',
                'status' => $bill->status,
                'created_at' => $bill->created_at->format('d-m-Y'),
                'expired_at' => $bill->expired_at ? $bill->expired_at->format('d-m-Y') : '-',
                'sub_total' => $bill->subTotal(),
                'total' => $bill->total(),
                'due_amount' => $bill->dueAmount(),
            ]
        ]);
    }

    /**
     * @param $slug
     * @param \App\Model\Invoice\Bill $bill
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function preview($slug, Bill $bill)
    {
        $this->authorize('view', $bill);

        return PDF::loadView('invoices.default', [
            'organization' => Organization::whereSlug($slug)->firstOrFail(),
            'bill' => $bill->load('client', 'articles', 'payments'),
            'sub_total' => $bill->subTotal(),
            'paid_date' => $bill->payments->sum->paid_out,
            'total' => $bill->total(),
            'due_amount' => $bill->dueAmount()
        ])->stream("Factura-{$bill->id}-{$bill->created_at->format('dmY')}");
    }
}

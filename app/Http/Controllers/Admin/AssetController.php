<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAssetRequest;
use App\Http\Requests\Admin\UpdateAssetRequest;
use App\Models\Asset;
use App\Notifications\AdminActionOccurred;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class AssetController extends Controller
{
    public function index(): View
    {
        $assets = Asset::query()->latest()->paginate(20);

        return view('admin.assets.index', compact('assets'));
    }

    public function create(): View
    {
        return view('admin.assets.create');
    }

    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $asset = Asset::query()->create($request->validated());

        // Notify admins
        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Asset Created', 'An asset was created: '.$asset->symbol));

        return redirect()->route('admin.assets.index')->with('status', 'Asset created');
    }

    public function edit(Asset $asset): View
    {
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(UpdateAssetRequest $request, Asset $asset): RedirectResponse
    {
        $asset->update($request->validated());

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Asset Updated', 'Asset updated: '.$asset->symbol));

        return redirect()->route('admin.assets.index')->with('status', 'Asset updated');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $symbol = $asset->symbol;
        $asset->delete();

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Asset Deleted', 'Asset deleted: '.$symbol));

        return back()->with('status', 'Asset deleted');
    }
}

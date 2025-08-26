<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAssetNetworkRequest;
use App\Http\Requests\Admin\UpdateAssetNetworkRequest;
use App\Models\Asset;
use App\Models\AssetNetwork;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class AssetNetworkController extends Controller
{
    public function index(): View
    {
        $query = AssetNetwork::query()->with('asset');

        if (request('asset_id')) {
            $query->where('asset_id', request('asset_id'));
        }

        $networks = $query->latest()->paginate(25);

        return view('admin.asset_networks.index', compact('networks'));
    }

    public function create(): View
    {
        $assets = Asset::query()->orderBy('symbol')->get(['id', 'symbol', 'name']);

        return view('admin.asset_networks.create', compact('assets'));
    }

    public function store(StoreAssetNetworkRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        $uploaded = request()->file('qr_file');
        if ($uploaded) {
            $path = Storage::disk('public')->putFile('qr', $uploaded);
            $data['qr_path'] = 'storage/' . $path;
        }

        unset($data['qr_file']);

        AssetNetwork::query()->create($data);

        return redirect()->route('admin.asset_networks.index')->with('status', 'Network created');
    }

    public function edit(AssetNetwork $asset_network): View
    {
        $assets = Asset::query()->orderBy('symbol')->get(['id', 'symbol', 'name']);

        return view('admin.asset_networks.edit', [
            'network' => $asset_network,
            'assets' => $assets,
        ]);
    }

    public function update(UpdateAssetNetworkRequest $request, AssetNetwork $asset_network): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        $uploaded = request()->file('qr_file');
        if ($uploaded) {
            $path = Storage::disk('public')->putFile('qr', $uploaded);
            $data['qr_path'] = 'storage/' . $path;
        }

        unset($data['qr_file']);

        $asset_network->update($data);

        return redirect()->route('admin.asset_networks.index')->with('status', 'Network updated');
    }

    public function destroy(AssetNetwork $asset_network): RedirectResponse
    {
        $asset_network->delete();

        return back()->with('status', 'Network deleted');
    }
}

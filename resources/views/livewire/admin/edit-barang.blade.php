<div class="utama container">
    <div class="card rounded-4 border-0 shadow-sm">
        <div class="card-header bg-primary rounded-top-4 text-white">
            <h5 class="mb-0">Edit Barang</h5>
        </div>

        <div class="card-body">
            @if (session()->has("success"))
                <div class="alert alert-success">
                    {{ session("success") }}
                </div>
            @endif

            <form wire:submit.prevent="update" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Stock Code</label>
                    <input type="text" class="form-control" wire:model.defer="stock_code">
                    @error("stock_code")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Part Number</label>
                    <input type="text" class="form-control" wire:model.defer="part_number">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mnemonic</label>
                    <input type="text" class="form-control" wire:model.defer="mnemonic">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" wire:model.defer="nama_barang">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" rows="3" wire:model.defer="deskripsi"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" wire:model="image">

                    @if ($old_image)
                        <img src="{{ asset("storage/" . $old_image) }}" class="mt-2 rounded" width="120">
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Note</label>
                    <input type="text" class="form-control" wire:model.defer="note">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" wire:model.defer="location">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Warehouse/Vendor</label>
                    <input type="text" class="form-control" wire:model.defer="warehouse">
                </div>

                <div class="col-md-4">
                    <label class="form-label">UOM</label>
                    <input type="text" class="form-control" wire:model.defer="uom">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number" class="form-control" wire:model.defer="qty">
                </div>

                <div class="col-md-3">
                    <label class="form-label">SOH Odoo</label>
                    <input type="number" class="form-control" wire:model.defer="soh_odoo">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Outstanding</label>
                    <input type="number" class="form-control" wire:model.defer="outstanding_belum_wr">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Difference</label>
                    <input type="number" class="form-control" wire:model.defer="difference">
                </div>

                <div class="col-12">
                    <label class="form-label">Remarks</label>
                    <textarea class="form-control" rows="2" wire:model.defer="remarks"></textarea>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>

            </form>
        </div>
    </div>

    <style>
        .utama {
            padding-top: 90px;
            padding-bottom: 70px;
        }
    </style>
</div>

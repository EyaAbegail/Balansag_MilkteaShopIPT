<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $drink->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Drink name</label>
        <input type="text" name="name" value="{{ old('name', $drink->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $drink->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Base price</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $drink->price ?? '') }}" class="form-control @error('price') is-invalid @enderror">
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $drink->stock ?? '') }}" class="form-control @error('stock') is-invalid @enderror">
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Image upload</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $drink->is_featured ?? false))>
            <label class="form-check-label" for="is_featured">Feature this drink</label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available" @checked(old('is_available', $drink->is_available ?? true))>
            <label class="form-check-label" for="is_available">Visible in menu</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-cta">Save drink</button>
        <a href="{{ route('admin.drinks.index') }}" class="btn btn-outline-dark">Cancel</a>
    </div>
</form>

@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Category Information</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.category') }}">
                            <div class="text-tiny">Category</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Category</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('store.category') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category name" name="name"
                            value="" tabindex="0"  aria-required="true" required="">
                    </fieldset>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category Slug" name="slug"
                            value="" tabindex="0"  aria-required="true" required="">
                    </fieldset>
                    @error('slug')
                        <span class="invalid-feedback" role="alert">
                            <strong class="error">{{ $message }}</strong>
                        </span>
                    @enderror
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('slug')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#myFile').on('change', function(e){
        const photoInp = $('#myFile');
        const file = photoInp[0].files[0];
        
        if(file){
            $('#imgpreview img').attr('src', URL.createObjectURL(file));
            $('#imgpreview').show();
        }
    });

    $('input[name="name"]').on('change', function(){
        $('input[name="slug"]').val(stringToSlug($(this).val()));
    });

    function stringToSlug(text){
        return text
            .toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    }
});
</script>

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {

    // ফাইল সিলেক্ট করলে প্রিভিউ দেখাও
    const fileInput = document.getElementById('myFile');
    const previewImg = document.getElementById('imgpreview');

    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            const url = URL.createObjectURL(file);
            previewImg.src = url;
            previewImg.style.display = 'block';
        }
    });

    // name ইনপুট থেকে slug তৈরি করে slug ইনপুটে বসাও
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    nameInput.addEventListener('input', function () {
        const nameValue = nameInput.value;
        const slug = stringToSlug(nameValue);
        slugInput.value = slug;
    });

    function stringToSlug(text) {
        return text
            .toLowerCase()
            .replace(/ /g, '-')          // স্পেস -> ড্যাশ
            .replace(/[^\w-]+/g, '');    // অক্ষর/সংখ্যা ছাড়া অন্য কিছু বাদ
    }

});
</script> --}}

@endpush

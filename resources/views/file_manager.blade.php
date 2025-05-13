@extends('layouts.app')
@section('content')
<style type="text/css">
    .innerbox .childbox {
        margin: 10px;
        /* border: 1px solid gray; */
        height: 25vh;
        align-items: center;
        text-align: center;
        justify-content: center;
        display: flex;
        padding: 20px;
        box-shadow: 0px 0px 5px 5px #80808017;
        overflow: hidden;
    }
    
    .childbox:hover {
        background-color: rgb(115 115 115 / 34%);
    }
    
    .innerbox .childbox a{
       width: 100%;
       word-wrap: break-word;
       text-decoration: none;
    }
     
    .file-icon {
        font-size: 60px;
    }
    .file-thumb {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 5px;
    }
    
    </style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">File Explorer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('file.manager') }}">🏠 Home</a>
                            @if ($current)
                            @php
                            $segments = explode('/', $current);
                            $breadcrumbPath = '';
                            @endphp

                            @foreach ($segments as $index => $segment)
                            @php
                            $breadcrumbPath .= ($index === 0) ? $segment : '/' . $segment;
                            @endphp

                            @if ($index !== count($segments) - 1)
                            / <a href="{{ route('file.manager', $breadcrumbPath) }}">{{ $segment }}</a>
                            @else
                            / {{ $segment }}
                            @endif
                            @endforeach
                            @endif
                        </div>

                        <div class="mt-3 col-md-12 d-flex flex-wrap file-boxes">
                            @if ($parent)
                            <div class="col-md-2 innerbox">
                                <div class="childbox">
                                    <a href="{{ route('file.manager', $parent) }}">
                                        <div class="file-icon">🔙</div>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @foreach($folders as $folder)
                            @php
                            $folderName = basename($folder);
                            $folderPath = $current ? $current . '/' . $folderName : $folderName;
                            $isEmpty = count(Storage::files('public/'. auth()->user()->id .'/' . $folderPath)) === 0 &&
                            count(Storage::directories('public/'. auth()->user()->id .'/'. $folderPath)) === 0;
                            @endphp
                            <div class="col-md-2 innerbox">
                                <div class="childbox">
                                    <a href="{{ route('file.manager', $folderPath) }}">
                                        <div class="file-icon">📁</div>
                                        {{ $folderName }}
                                    </a>
                                     {{-- @if($isEmpty)
                                    <form method="POST" action="{{ route('file.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $folderPath }}">
                                        <button type="submit" class="btn btn-sm btn-danger mt-1">🗑</button>
                                    </form>
                                    @endif --}}
                                </div>
                            </div>
                            @endforeach

                            @foreach($files as $file)
                            <div class="col-md-2 innerbox">
                                <div class="childbox">
                                    
                                    <a
                                        href="{{ route('file.download', ($current ? $current . '/' : '') . $file->getFilename()) }}">
                                        @php
                                        $ext = strtolower($file->getExtension());
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                        $isImage = in_array($ext, $imageExtensions);
                                        $fileUrl = asset('storage/'. auth()->user()->id . '/'   . ($current ? $current . '/' : '') . $file->getFilename());
                                        
                                        @endphp

                                        @if($isImage)
                                        <img src="{{ $fileUrl }}" alt="{{ $file->getFilename() }}" class="file-thumb">
                                        @else
                                        <div class="file-icon">📄</div>
                                        @endif

                                        {{ $file->getFilename() }}
                                    </a>
                                    {{--  <form method="POST" action="{{ route('file.delete') }}" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path"
                                            value="{{ $current ? $current . '/' . $file->getFilename() : $file->getFilename() }}">
                                        <button type="submit" class="btn btn-sm btn-danger">🗑</button>
                                    </form> --}}

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                    <div id="loader" class="text-center my-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('js')
<script>
let page = 1;
let loading = false;

window.onscroll = function () {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200 && !loading) {
        loading = true;
        page++;
        document.getElementById('loader').style.display = 'block';
        loadMoreData(page);
    }
};

function loadMoreData(page) {
    let current = "{{ $current }}";
    let url = current ? `/file-manager/${current}?page=${page}` : `/file-manager?page=${page}`;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('loader').style.display = 'none';

        if (data.trim().length === 0) {
            return;
        }

        document.querySelector('.file-boxes').insertAdjacentHTML('beforeend', data);
        loading = false;
    })
    .catch(error => {
        console.error(error);
        document.getElementById('loader').style.display = 'none';
        loading = false;
    });
}
</script>

@endpush
@endsection
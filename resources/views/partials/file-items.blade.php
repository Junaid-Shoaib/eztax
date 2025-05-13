
@foreach($folders as $folder)
@php
    $folderName = basename($folder);
    $folderPath = $folder ? $folder . '/' . $folderName : $folderName;
@endphp
<div class="col-md-2 innerbox">
    <div class="childbox">
        <a href="{{ route('file.manager', $folderPath) }}">
            <div class="file-icon">📁</div>
            {{ $folderName }}
        </a>
        {{--
        @if($isEmpty)
        <form method="POST" action="{{ route('file.delete') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="path" value="{{ $folderPath }}">
            <button type="submit" class="btn btn-sm btn-danger mt-1">🗑</button>
        </form>
        @endif
        --}}
    </div>
</div>
@endforeach

@foreach($files as $file)
@php
    $ext = strtolower($file->getExtension());
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $isImage = in_array($ext, $imageExtensions);
    $fileUrl = asset('storage/'. auth()->user()->id . '/' . ($folder ? $folder . '/' : '') . $file->getFilename());
@endphp
<div class="col-md-2 innerbox">
    <div class="childbox">
        <a href="{{ route('file.download', ($folder ? $folder . '/' : '') . $file->getFilename()) }}">
            @if($isImage)
            <img src="{{ $fileUrl }}" class="file-thumb" />
            @else
            <div class="file-icon">📄</div>
            @endif
            {{ $file->getFilename() }}
        </a>
        {{-- 
        <form method="POST" action="{{ route('file.delete') }}" class="mt-1">
            @csrf
            @method('DELETE')
            <input type="hidden" name="path" value="{{ $current ? $current . '/' . $file->getFilename() : $file->getFilename() }}">
            <button type="submit" class="btn btn-sm btn-danger">🗑</button>
        </form>
        --}}
    </div>
</div>
@endforeach
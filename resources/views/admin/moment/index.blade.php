@extends('admin.layouts.app')
@section('title', 'Konten Moment')
@section('breadcrumb', 'Konten Moment')
@section('content')
<div class="space-y-6">
 <div><h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">Konten Moment</h1><p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">Kelola foto, paket, harga, koleksi, dan kontak. Perubahan langsung tampil setelah disimpan.</p><a class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-50 focus:outline-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700 mt-4" href="{{ request()->getScheme().'://'.config('moment.domain').(request()->getPort()!=80 && request()->getPort()!=443 ? ':'.request()->getPort() : '') }}" target="_blank" rel="noopener">Lihat Website Moment ↗</a></div>
 @if(session('success'))<p class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-950/40 dark:text-green-300">{{ session('success') }}</p>@endif
 @if($errors->any())<div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
 <nav class="flex flex-wrap gap-2 rounded-xl border border-gray-200 bg-white p-3 shadow-sm dark:border-neutral-700 dark:bg-neutral-800" aria-label="Bagian konten Moment">@foreach(['home'=>'Foto Beranda','packages'=>'Paket','collections'=>'Preset & Desain','contact'=>'Kontak','media'=>'Media'] as $key=>$label)<a class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $tab===$key ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400':'text-gray-600 hover:bg-gray-100 hover:text-gray-800 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200' }}" @if($tab===$key) aria-current="page" @endif href="{{ route('admin.moment.index',['tab'=>$key]) }}">{{ $label }}</a>@endforeach</nav>
 @if(isset($sections[$tab]))
 <form method="POST" action="{{ route('admin.moment.settings',$tab) }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5 text-gray-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">@csrf @method('PUT')
 @foreach($sections[$tab] as $key)
  @if($key==='hero')
   @include('admin.moment.media-select',['field'=>$key,'value'=>$settings[$key]??'','label'=>'Foto utama beranda'])
  @else
   <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{ ['address'=>'Alamat','maps_url'=>'Link Google Maps (opsional)','phone'=>'Nomor telepon','whatsapp'=>'WhatsApp (format 62xxxxxxxxxx)','instagram_url'=>'Link Instagram (opsional)','facebook_url'=>'Link Facebook (opsional)','threads_url'=>'Link Threads (opsional)','tiktok_url'=>'Link TikTok (opsional)'][$key] }}<textarea class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500" name="{{ $key }}" rows="2">{{ old($key,$settings[$key]??'') }}</textarea></label>
  @endif
 @endforeach
 <button class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">Simpan perubahan</button></form>
 @elseif(in_array($tab,['packages','collections']))
 <section class="space-y-4" aria-labelledby="moment-entry-list-title">
 <h2 id="moment-entry-list-title" class="text-base font-semibold text-gray-800 dark:text-neutral-200">{{ $tab==='packages'?'Daftar Paket':'Daftar Preset & Desain' }}</h2>
 @foreach(($tab==='packages'?$packages:$collections) as $entry)
 <details class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm text-gray-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"><summary class="cursor-pointer text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $entry->name }} {{ !$entry->is_active ? '(Nonaktif)' : '' }}</summary>
 @include('admin.moment.entry-form')
 </details>
 @endforeach
 </section>
 <div class="border-t border-gray-200 pt-6 dark:border-neutral-700">
  <section class="rounded-xl border-2 border-dashed border-blue-200 bg-blue-50/50 p-4 text-gray-800 sm:p-6 dark:border-blue-500/40 dark:bg-blue-500/5 dark:text-neutral-200" aria-labelledby="moment-create-title">
   <div class="flex items-start gap-3">
    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400" aria-hidden="true"><svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg></span>
    <div>
     <h2 id="moment-create-title" class="text-base font-semibold text-blue-800 dark:text-blue-300">{{ $tab==='packages'?'Tambah Paket Baru':'Tambah Preset / Desain' }}</h2>
     <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">{{ $tab==='packages'?'Isi form berikut untuk menambahkan paket foto baru.':'Isi form berikut untuk menambahkan preset atau desain baru.' }}</p>
    </div>
   </div>
   @include('admin.moment.entry-form', ['entry' => null])
  </section>
 </div>
 @else
 <form method="POST" action="{{ route('admin.moment.media.store') }}" enctype="multipart/form-data" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4 text-gray-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">@csrf<h2 class="font-semibold">Upload gambar</h2><p class="text-sm">JPG, PNG, WebP. Maksimal 5 MB. Untuk mengganti foto, upload lalu pilih di form konten.</p><input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="block w-full rounded-lg border border-gray-200 text-sm text-gray-700 file:me-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-medium hover:file:bg-gray-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:file:bg-neutral-700 dark:file:text-neutral-200"><input class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500" name="alt_text" placeholder="Deskripsi gambar" required><button class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">Upload</button></form>
 <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">@foreach($media as $image)<section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm space-y-3 text-gray-800 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"><img class="h-48 w-full rounded-lg bg-gray-50 object-contain dark:bg-neutral-900" src="{{ \App\Services\MomentContent::imageUrl($image->path) }}" alt="{{ $image->alt_text }}"><p class="text-xs break-all text-gray-500 dark:text-neutral-400">{{ basename($image->path) }} · {{ $image->width }} × {{ $image->height }}</p><form method="POST" action="{{ route('admin.moment.media.update',$image) }}" class="space-y-3">@csrf @method('PUT')<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Deskripsi<input class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500" name="alt_text" value="{{ $image->alt_text }}" required></label><button class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-50 focus:outline-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700">Simpan informasi</button></form>@include('admin.moment.delete-media')</section>@endforeach</div>
 @endif
</div>
@if(in_array($tab,['home','packages','collections']))
@include('admin.moment.media-library')
@endif
@endsection

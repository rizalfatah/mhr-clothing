@php
$data=$entry?->data??[];
if(old('_entry') === ($entry ? (string)$entry->id : 'new')) {
 $data=array_replace($data,old());
 $data['benefits']=is_string($data['benefits']??null)?preg_split('/\R/',$data['benefits']):($data['benefits']??[]);
}
@endphp
<form method="POST" action="{{ $entry ? route('admin.moment.update',[$tab,$entry->id]):route('admin.moment.store',$tab) }}" class="mt-5 space-y-4">
<input type="hidden" name="_entry" value="{{ $entry?->id??'new' }}">@csrf @if($entry) @method('PUT') @endif
@if($entry)<p class="text-xs text-gray-500 dark:text-neutral-400">URL tetap: /{{ $entry->slug }}</p>@endif
@foreach(['name'=>'Nama','description'=>'Deskripsi'] + ($tab==='packages'?['price'=>'Harga (rupiah)']:[]) as $key=>$label)
<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $label }}@if($key==='price')<input type="number" min="0" max="1000000000" name="price" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500" value="{{ $data['price']??'' }}" required>@else<textarea name="{{ $key }}" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500" rows="2">{{ $key==='name'?($data['name']??$entry?->name):($data[$key]??'') }}</textarea>@endif</label>
@endforeach
@include('admin.moment.media-select',['field'=>'image','value'=>$data['image']??'','label'=>'Foto cover / preview'])
@if($tab==='packages')
<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Status harga<select name="price_status" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500"><option value="estimate" @selected(($data['price_status']??'estimate')==='estimate')>Estimasi</option><option value="final" @selected(($data['price_status']??'')==='final')>Harga final</option></select></label>
<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Benefit — satu baris per benefit, sesuai urutan<textarea name="benefits" rows="9" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500">{{ implode("\n",$data['benefits']??[]) }}</textarea></label>
<details><summary class="py-3 cursor-pointer">Galeri — pilih sesuai urutan tampil</summary><div class="space-y-3">@for($i=0;$i<min(30,max(8,count($data['gallery']??[])+2));$i++)@include('admin.moment.media-select',['field'=>'gallery[]','value'=>$data['gallery'][$i]??'','label'=>'Foto '.($i+1)])@endfor</div></details>
@else
<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Jenis<select name="type" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500">@foreach(['preset'=>'Preset','design'=>'Desain'] as $key=>$label)<option value="{{ $key }}" @selected(($data['type']??'')===$key)>{{ $label }}</option>@endforeach</select></label>

@endif
<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Urutan (angka kecil lebih dahulu)<input type="number" min="0" name="sort_order" value="{{ $entry?->sort_order??0 }}" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500" required></label>
<label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Status<select name="is_active" class="block mt-2 w-full rounded-lg border-gray-200 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:placeholder-neutral-500"><option value="1" @selected(!$entry || $entry->is_active)>Aktif</option><option value="0" @selected($entry && !$entry->is_active)>Nonaktif</option></select></label>
<button class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">{{ $entry?'Simpan':($tab==='packages'?'Tambah Paket':'Tambah Preset / Desain') }}</button>
</form>

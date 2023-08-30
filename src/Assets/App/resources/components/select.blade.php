@php use Illuminate\Support\Arr;use Thinktomorrow\Chief\Forms\Fields\Common\FormKey; @endphp
<div>
    <div>
        <!-- Once the temp files are stored, this field is populated with the associated media record id -->
        @foreach($getFilesForUpload() as $i => $file)
            <div wire:key="files_for_upload_{{$file->id}}">
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][id]" value="{{$file->id}}"/>
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][path]" value="{{$file->tempPath}}"/>
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][originalName]"
                       value="{{$file->filename}}"/>
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][mimeType]"
                       value="{{$file->mimeType}}"/>

                @foreach($file->fieldValues as $fieldKey => $fieldValue)
                    @if(is_array($fieldValue))
                        @foreach($fieldValue as $key => $value)
                            <input type="hidden"
                                   name="{{ $getFieldName() }}[uploads][{{ $i }}][fieldValues][{{ $fieldKey }}][{{ $key }}]"
                                   value="{{$value}}"/>
                        @endforeach
                    @else
                        <input type="hidden"
                               name="{{ $getFieldName() }}[uploads][{{ $i }}][fieldValues][{{ $fieldKey }}]"
                               value="{{$fieldValue}}"/>
                    @endif
                @endforeach
            </div>
        @endforeach

        @foreach($getFilesForAttach() as $i => $file)
            <div wire:key="files_for_attach_{{$file->id}}">
                <input type="hidden" name="{{ $getFieldName() }}[attach][{{ $i }}][id]" value="{{ $file->id }}">
                @foreach(Arr::dot($file->fieldValues) as $fieldKey => $fieldValue)
                    <input type="hidden"
                           name="{{ FormKey::replaceDotsByBrackets($getFieldName().'.attach.'.$i.'.fieldValues.' . $fieldKey) }}]"
                           value="{{$fieldValue}}"/>
                    {{--                    @if(is_array($fieldValue))--}}
                    {{--                        @foreach($fieldValue as $key => $value)--}}
                    {{--                            <input type="hidden"--}}
                    {{--                                   name="{{ $getFieldName() }}[attach][{{ $i }}][fieldValues][{{ $fieldKey }}][{{ $key }}]"--}}
                    {{--                                   value="{{$value}}"/>--}}
                    {{--                        @endforeach--}}
                    {{--                    @else--}}
                    {{--                        <input type="hidden"--}}
                    {{--                               name="{{ $getFieldName() }}[attach][{{ $i }}][fieldValues][{{ $fieldKey }}]"--}}
                    {{--                               value="{{$fieldValue}}"/>--}}
                    {{--                    @endif--}}
                @endforeach
            </div>
        @endforeach

        @foreach($getFilesForDeletion() as $i => $file)
            <input wire:key="files_for_deletion_{{$file->id}}" type="hidden"
                   name="{{ $getFieldName() }}[queued_for_deletion][{{ $i }}]" value="{{ $file->id }}">
        @endforeach

        @foreach($getFiles() as $i => $file)
            <input wire:key="files_for_order_{{$file->id}}" type="hidden" name="{{ $getFieldName() }}[order][{{ $i }}]"
                   value="{{ $file->id }}">
        @endforeach
    </div>

    @if($getFilesCount() == 0)
        @include('chief-assets::_partials.select-empty')
    @else
        @include('chief-assets::_partials.select-buttons')
    @endif

    <div>
        @foreach($errors->all() as $error)
            <x-chief::inline-notification type="error" class="mt-2">
                {{ ucfirst($error) }}
            </x-chief::inline-notification>
        @endforeach
    </div>
</div>

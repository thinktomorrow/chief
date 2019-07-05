@foreach($tab->fields() as $field)
    {!! $manager->renderField($field) !!}
@endforeach

@if(count($redirects) > 0)
    <h3>Redirects</h3>
    <p>Dit zijn alle links die doorlinken naar deze pagina. </p>
    <div>
        @foreach($redirects as $redirect)
            <url-redirect inline-template removeurl="{{ route('chief.back.assistants.url.remove-redirect',$redirect->getUrlRecordId()) }}">
                <div v-show="!this.removed" class="bg-white panel inset-xs stack-s">
                    <span class="block btn btn-round btn-tertiary cursor-pointer text-tertiary font-s" style="float:right; padding:2px 5px;" @click="remove">verwijder</span>
                    <div>{{ $redirect->fullUrl() }}</div>
                </div>
            </url-redirect>
        @endforeach
    </div>

@endif


@push('custom-components')
    <script>
        Vue.component('url-redirect',{
            props: ['fieldProp', 'removeurl'],
            data: function(){
                return {
                    removed: false,
                };
            },
            methods: {
                remove: function(){

                    const self = this;

                    window.axios.post(this.removeurl, {
                        _method: 'DELETE',
                    }).then(function({data}){
                        self.removed = true;
                    });
                }
            }

        });
    </script>
@endpush
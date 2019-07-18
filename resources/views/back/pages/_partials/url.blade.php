@foreach($tab->fields() as $field)
    {!! $manager->renderField($field) !!}
@endforeach

@if(count($redirects) > 0)
    <h3>Redirects</h3>
    <p>Dit zijn alle links die doorlinken naar deze pagina. </p>
    <div>
        @foreach($redirects as $redirect)
            <url-redirect inline-template removeurl="{{ route('chief.back.assistants.url.remove-redirect',$redirect->getUrlRecordId()) }}">
                <div v-show="!this.removed" class="bg-white panel inset-s stack-s">
                    <span class="text-error cursor-pointer" style="float:right; padding:2px 5px;" @click="remove">
                        <svg class="fill-current" width="18" height="18"><use xlink:href="#trash"/></svg>
                    </span>
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
<?php
    $array = collect($managers->items())->map(function($item){
        return [
            'id'=> $item->model()->flatreference()->get(),
            'label'=> $item->details()->title
        ];
    })->toArray();
?>

<h3 class="mt-8">Sorteren</h3>
<p class="mb-4">Hier nu kunde sorteren</p>

<div class="bg-grey-100 border-b border-l border-r border-grey-100">
    <sortable-list :items='@json($array)' v-slot:="{ item }">
            <sortable-handle>
                <sortable-item>
                    <div class="flex justify-between items-center p-4 bg-white border-grey-100 border-t">
                        <p>@{{ item.label }}</p>
                    </div>
                </sortable-item>
            </sortable-handle>
    </sortable-list>
</div>

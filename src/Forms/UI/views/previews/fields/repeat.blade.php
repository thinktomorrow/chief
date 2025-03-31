<div class="@container/nested rounded-xl border border-grey-100 px-3 py-2">
    <table class="w-full">
        <tbody class="divide-y divide-grey-100 [&>*:not(:first-child)>td]:pt-2 [&>*:not(:last-child)>td]:pb-2">
            @foreach ($getRepeatedComponents($locale ?? null) as $components)
                <tr>
                    @foreach ($components as $childComponent)
                        <td class="[&_tr:not(:first-child)]:pt-1 [&_tr:not(:last-child)]:pb-1">
                            {{ $childComponent->renderPreview() }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

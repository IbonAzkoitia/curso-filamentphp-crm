@switch($leadStatus->name)
@case('Nurturing')
<div
    class="inline-flex items-center rounded-md px-2 py-1 dark:text-gray-950 text-xs font-medium ring-1 ring-inset bg-gray-50 text-gray-80 ring-gray-600/20">
    {{ $leadStatus->name }}
</div>
@break
@case('Analizar')
<div
    class="inline-flex items-center rounded-md px-2 py-1 dark:text-gray-950 text-xs font-medium ring-1 ring-inset bg-yellow-50 text-yellow-80 ring-yellow-600/20">
    {{ $leadStatus->name }}
</div>
@break
@case('Trabajando')
<div
    class="inline-flex items-center rounded-md px-2 py-1 dark:text-gray-950 text-xs font-medium ring-1 ring-inset bg-green-50 text-green-80 ring-green-600/20">
    {{ $leadStatus->name }}
</div>
@break
@case('Discovery Call')
<div
    class="inline-flex items-center rounded-md px-2 py-1 dark:text-gray-950 text-xs font-medium ring-1 ring-inset bg-blue-50 text-blue-80 ring-blue-600/20">
    {{ $leadStatus->name }}
</div>
@break
@case('Referido')
<div
    class="inline-flex items-center rounded-md px-2 py-1 dark:text-gray-950 text-xs font-medium ring-1 ring-inset bg-purple-50 text-purple-80 ring-purple-600/20">
    {{ $leadStatus->name }}
</div>
@break
@case('Descartado')
<div
    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset bg-red-50 text-red-80 ring-red-600/20">
    {{ $leadStatus->name }}
</div>
@break
@endswitch
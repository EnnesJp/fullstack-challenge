<x-mail::message>
# Expense Registered

Hi {{ $user }}! Your expense '{{ $expense->description }}' has been registered.


<x-mail::button :url="$url">
View Expense
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

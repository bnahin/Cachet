@component('mail::message')
    # Automated Service Status Change

    The service <strong>{{ $data['name'] }}</strong> is now <strong style="color:{{ $data['statusColor'] }}">
        {{ $data['status'] }} </strong>.

    @if ($data['status'] == 'down')
        It was up since {{ $data->revTime->toFormattedDateString() }}.

        The component is currently listed as Performance Issues.
        After 3 minutes of downtime, the component's status will change to a Partial Outage.
        After 10 minutes, the status will change to Major Outage.
    @else
        It was up since {{ $data['prevTime']->toFormattedDateString() }}.

    @endif

@endcomponent
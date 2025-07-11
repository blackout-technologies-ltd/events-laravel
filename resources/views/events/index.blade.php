<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .user-section {
            margin-bottom: 40px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }
        .user-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #495057;
        }
        .org-name {
            color: #007bff;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .valence-good {
            color: #28a745;
            font-weight: bold;
        }
        .valence-bad {
            color: #dc3545;
            font-weight: bold;
        }
        .active-event {
            background-color: #fff3cd;
        }
        .closed-event {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-closed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .no-events {
            text-align: center;
            color: #6c757d;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Events Table by User</h1>
        
        @foreach($users as $user)
            <div class="user-section">
                <div class="user-header">
                    {{ $user->name }} ({{ $user->email }}) - <span class="org-name">{{ $user->org->name }}</span> - {{ $user->events->count() }} events
                </div>
                
                @if($user->events->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Valence</th>
                                <th>Valid From (UTC)</th>
                                <th>Valid To (UTC)</th>
                                <th>Status</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->events as $event)
                                <tr class="{{ is_null($event->valid_to) ? 'active-event' : 'closed-event' }}">
                                    <td>{{ ucfirst($event->type) }}</td>
                                    <td class="valence-{{ strtolower($event->valence) }}">
                                        {{ $event->valence }}
                                    </td>
                                    <td>{{ $event->valid_from->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        {{ $event->valid_to ? $event->valid_to->format('Y-m-d H:i:s') : 'Still Active' }}
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ is_null($event->valid_to) ? 'active' : 'closed' }}">
                                            {{ is_null($event->valid_to) ? 'ACTIVE' : 'CLOSED' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($event->valid_to)
                                            {{ $event->valid_from->diffForHumans($event->valid_to, true) }}
                                        @else
                                            {{ $event->valid_from->diffForHumans(null, true) }} (ongoing)
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-events">No events found for this user</div>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
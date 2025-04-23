
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container">
    <h2>Create New Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Ticket Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Ticket Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="assigned_to">Assign to</label>
            <select class="form-control" id="assigned_to" name="assigned_to" required>
                <!-- List user options -->
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Ticket</button>
    </form>
</div>
</body>
</html>
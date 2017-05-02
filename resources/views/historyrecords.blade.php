@php
    echo "<h3>All historial actions</h3>";
    echo "<table border='1'>";
    echo "<tr><td>Timestamp</td><td>From</td><td>To</td><td></td></tr>";
    if(sizeof($records)>0){
        $record = $records[0];
        echo "<tr><td>$record->created_at</td>$record->description<td><a href='".route('org.revert')."?hid=$record->id'>UNDO</a></td></tr>";
    }
    if(sizeof($records)>1){
        for($i = 1; $i < sizeof($records); $i++){
            $record = $records[$i];
            echo "<tr><td>$record->created_at</td>$record->description<td>UNDO</td></tr>";
        }
    }
    echo "</table>";
@endphp
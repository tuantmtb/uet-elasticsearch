@php
    echo "<h3>All organizations</h3>";
    echo "<ul style='list-style-type: none;'>";
    foreach ($orgsView as $orgId => $orgFullName){
        echo "<li><a href='".route('org.similar')."?oid=$orgId'>$orgFullName</a></li>";
    }
    echo "</ul>";
@endphp
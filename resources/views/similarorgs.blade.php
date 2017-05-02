@php
    echo "<a href=\"javascript:history.go(-1)\">GO BACK</a>|<a href='".route('org.history')."'>HISTORY</a><br/>";
    echo "Proposed for <b>$orgName</b>:</br>";
    if (sizeof($similarOrgsProposed)-1 > 0){
        echo Form::open(array('route' => 'org.merge'));
        echo Form::number('sto', $orgId, array("style"=>'display:none'));
        echo "<ul style='list-style-type: none;'>";
        foreach ($similarOrgsProposed as $org => $orgFullName){
            if(intval($org)!=intval($orgId)){
                echo "<li>".Form::checkbox('oids[]', $org)."<a href='".route('org.similar')."?oid=$org'>$orgFullName</a>"."</li>";
            }
        }
        echo "</ul>";
        echo Form::submit();
        echo Form::close();
    }else{
        echo "Opp! I did not find any organization similar to this one";
    }
@endphp

function ltrim(s) { sub(/^[ \t\r\n]+/, "", s); return s }
function rtrim(s) { sub(/[ \t\r\n]+$/, "", s); return s }
function sstrim(s) { sub(/[ ]{2,}/, "", s); return s }
function trim(s)  { return sstrim(rtrim(ltrim(s))); }
BEGIN {FS=OFS="\t"}
{print "insert into employees (name,position,reason,employment,comment,updated_by,employee_id) values ('" trim($1) "','" trim($2) "','" trim($3) "','" trim($4) "','" trim($5) "',1,(SELECT MAX(employee_id) FROM employees AS e2)+1);" }
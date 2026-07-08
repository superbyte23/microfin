$output = & "C:\Users\Windows\Desktop\microfin\vendor\bin\pest" --no-interaction 2>&1 | Out-String
Write-Output $output
$output | Out-File -FilePath "$env:TEMP\pest_output.txt" -Encoding utf8

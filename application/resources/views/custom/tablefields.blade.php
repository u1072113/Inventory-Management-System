@foreach($model as $table)
   <td>@{{ ${!!$table->loop!!}->{!!$table->columnName!!} }}</td>
 @endforeach
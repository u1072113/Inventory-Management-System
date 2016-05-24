<li class="dropdown messages-menu">
<!--     <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">{{$emailsCount}}</span>
    </a> -->
    <ul class="dropdown-menu">
        <li class="header">You have {{$emailsCount}} messages</li>
        <li>
            <!-- inner menu: contains the actual data -->

                <ul class="menu">
                    @foreach($emails as $email)
                        <li><!-- start message -->
                            <a href="#">
                                <div class="pull-left">
                                    <img alt="User Image" class="img-circle" src="{{App\Helper::avatar()}}">
                                </div>
                                <h4>
                                    {{$email->messageFrom}}
                                    <small><i class="fa fa-clock-o"></i> {{Carbon::createFromTimeStamp(strtotime($email->created_at))->diffForHumans()}}</small>
                                </h4>
                                <p><small>{{$email->text}}</small></p>
                            </a>
                        </li><!-- end message -->
                    @endforeach

                </ul>
        </li>
        <li class="footer">Mesages will Autoclear if issues are sorted</li>
    </ul>
</li>
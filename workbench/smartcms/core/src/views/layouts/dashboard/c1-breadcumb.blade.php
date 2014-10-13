<div class="row" id='breadcrumb'>
        <div class="col-sm-12">                
                <!-- start: PAGE TITLE & BREADCRUMB -->
                <ol class="breadcrumb">
                        <!--
                        <li>
                            <i class="clip-home-3"></i>
                            <?php if(count($links)):?>
                                <a href="{{ URL::route('indexDashboard') }}">Dashboard</a>
                            <?php else:?>
                                My Dashboard
                            <?php endif;?>    
                        </li>
                        -->
                        <?php if(count($links)):?>
                            <?php $index = 0; $count_links = count($links); ?>
                            <?php foreach($links as $link): $index++;?>
                                <?php if($index == $count_links):?>
                                    <li class="active">
                                        <i class='{{ $link['icon'] }}'></i>
                                        <?php echo $link['title'];?>
                                    </li>
                                <?php else:?>
                                    <li> 
                                        <a href='{{ $link['link'] }}'>
                                            <i class='{{ $link['icon'] }}'></i>
                                            <?php echo $link['title'];?>
                                        </a>
                                    </li>
                                <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>
                        <!--
                        <li class="search-box">
                                <form class="sidebar-search">
                                        <div class="form-group">
                                                <input type="text" placeholder="Start Searching...">
                                                <button class="submit">
                                                        <i class="clip-search-3"></i>
                                                </button>
                                        </div>
                                </form>
                        </li>
                        -->
                </ol>
                @if(isset($header_text) && $header_text)
                <div class="page-header">                    
                    <h1> {{$header_text}}</h1>
                </div>
                @endif
                <!-- end: PAGE TITLE & BREADCRUMB -->
        </div>
</div>
<!-- end: PAGE HEADER -->

<?php 
 
foreach ($menus as $key=>$menu){ 
    $menu = (object) $menu; 
    ?>
<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span> <?php echo $menu->label; ?> </span>
    <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
        <span data-feather="plus-circle"></span>
    </a>
</h6>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link " href="?example=<?php echo $key; ?>">
            <span data-feather="home"></span>
            Example  <span class="sr-only">(current)</span>
        </a>
    </li>
       <li class="nav-item">
        <a class="nav-link" href="?example=<?php echo $key; ?>#result">
            <span data-feather="file"></span>
            Result
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="?example=<?php echo $key; ?>#code">
            <span data-feather="file"></span>
            Code
        </a>
    </li>
</ul>
<?php 
}
?>

 
<!--
<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>With Javascript  </span>
    <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
        <span data-feather="plus-circle"></span>
    </a>
</h6>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link active" href="#quilljs_editor">
            <span data-feather="home"></span>
            Example  <span class="sr-only">(current)</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#section_2">
            <span data-feather="file"></span>
            Code
        </a>
    </li>

</ul>-->
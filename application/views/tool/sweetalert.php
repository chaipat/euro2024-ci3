<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Test </title>

	<meta name="theme-color" content="#f2f4f6">
    
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">
    <link rel="stylesheet" href="https://sweetalert2.github.io/styles/styles.css">
    <link rel="stylesheet" href="https://sweetalert2.github.io/styles/bootstrap4-buttons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
</head>
<body>

    <h3 id="examples">Examples</h3>

    <ul class="examples">

        <li id="message-example">
        <div class="ui">
            <p>A basic message</p>
            <button class="show-example-btn" aria-label="Try me! Example: A basic message" onclick="executeExample('basicMessage')">
            Try me!
            </button>
        </div>
        <pre data-example-id="basicMessage" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>(<span class="hljs-string">'Any fool can use a computer'</span>)</code></pre>
        </li>

        <li id="title-text-example">
        <div class="ui">
            <p>A title with a text under</p>
            <button class="show-example-btn" aria-label="Try me! Example: A title with a text under" onclick="executeExample('titleText')">
            Try me!
            </button>
        </div>
        <pre data-example-id="titleText" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>(
    <span class="hljs-string">'The Internet?'</span>,
    <span class="hljs-string">'That thing is still around?'</span>,
    <span class="hljs-string">'question'</span>
    )</code></pre>
        </li>

        <li id="serror-example">
        <div class="ui">
            <p>A modal with a title, an error icon, a text, and a footer</p>
            <button class="show-example-btn" aria-label="Try me! Example: A modal with a title, an error icon, a text, and a footer" onclick="executeExample('errorType')">
            Try me!
            </button>
        </div>
        <pre data-example-id="errorType" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">icon</span>: <span class="hljs-string">'error'</span>,
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Oops...'</span>,
    <span class="hljs-attr">text</span>: <span class="hljs-string">'Something went wrong!'</span>,
    <span class="hljs-attr">footer</span>: <span class="hljs-string">'&lt;a href=""&gt;Why do I have this issue?&lt;/a&gt;'</span>
    })</code></pre>
        </li>

        <li id="long-text">
        <div class="ui">
            <p>A modal window with a long content inside:</p>
            <button class="show-example-btn" aria-label="Try me! Example: A modal window with a long content inside" onclick="executeExample('longText')">
            Try me!
            </button>
        </div>
        <pre data-example-id="longText" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">imageUrl</span>: <span class="hljs-string">'https://placeholder.pics/svg/300x1500'</span>,
    <span class="hljs-attr">imageHeight</span>: <span class="hljs-number">1500</span>,
    <span class="hljs-attr">imageAlt</span>: <span class="hljs-string">'A tall image'</span>
    })</code></pre>
        </li>

        <li id="custom-html">
        <div class="ui">
            <p>Custom HTML description and buttons with ARIA labels</p>
            <button class="show-example-btn" aria-label="Try me! Example: Custom HTML description and buttons" onclick="executeExample('customHtml')">
            Try me!
            </button>
        </div>
        <pre data-example-id="customHtml" data-codepen-css-external="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'&lt;strong&gt;HTML &lt;u&gt;example&lt;/u&gt;&lt;/strong&gt;'</span>,
    <span class="hljs-attr">icon</span>: <span class="hljs-string">'info'</span>,
    <span class="hljs-attr">html</span>:
        <span class="hljs-string">'You can use &lt;b&gt;bold text&lt;/b&gt;, '</span> +
        <span class="hljs-string">'&lt;a href="//sweetalert2.github.io"&gt;links&lt;/a&gt; '</span> +
        <span class="hljs-string">'and other HTML tags'</span>,
    <span class="hljs-attr">showCloseButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">showCancelButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">focusConfirm</span>: <span class="hljs-literal">false</span>,
    <span class="hljs-attr">confirmButtonText</span>:
        <span class="hljs-string">'&lt;i class="fa fa-thumbs-up"&gt;&lt;/i&gt; Great!'</span>,
    <span class="hljs-attr">confirmButtonAriaLabel</span>: <span class="hljs-string">'Thumbs up, great!'</span>,
    <span class="hljs-attr">cancelButtonText</span>:
        <span class="hljs-string">'&lt;i class="fa fa-thumbs-down"&gt;&lt;/i&gt;'</span>,
    <span class="hljs-attr">cancelButtonAriaLabel</span>: <span class="hljs-string">'Thumbs down'</span>
    })</code></pre>
        </li>

        <li id="three-buttons">
        <div class="ui">
            <p>A dialog with three buttons</p>
            <button class="show-example-btn" aria-label="Try me! Example: A dialog with three buttons" onclick="executeExample('threeButtons')">
            Try me!
            </button>
        </div>
        <pre data-example-id="threeButtons" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Do you want to save the changes?'</span>,
    <span class="hljs-attr">showDenyButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">showCancelButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">confirmButtonText</span>: <span class="hljs-string">'Save'</span>,
    <span class="hljs-attr">denyButtonText</span>: <span class="hljs-string">`Don't save`</span>,
    }).<span class="hljs-title function_">then</span>(<span class="hljs-function">(<span class="hljs-params">result</span>) =&gt;</span> {
    <span class="hljs-comment">/* Read more about isConfirmed, isDenied below */</span>
    <span class="hljs-keyword">if</span> (result.<span class="hljs-property">isConfirmed</span>) {
        <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>(<span class="hljs-string">'Saved!'</span>, <span class="hljs-string">''</span>, <span class="hljs-string">'success'</span>)
    } <span class="hljs-keyword">else</span> <span class="hljs-keyword">if</span> (result.<span class="hljs-property">isDenied</span>) {
        <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>(<span class="hljs-string">'Changes are not saved'</span>, <span class="hljs-string">''</span>, <span class="hljs-string">'info'</span>)
    }
    })</code></pre>
        </li>

        <li id="custom-position">
        <div class="ui">
            <p>A custom positioned dialog</p>
            <button class="show-example-btn" aria-label="Try me! Example: A custom positioned dialog" onclick="executeExample('customPosition')">
            Try me!
            </button>
        </div>
        <pre data-example-id="customPosition" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">position</span>: <span class="hljs-string">'top-end'</span>,
    <span class="hljs-attr">icon</span>: <span class="hljs-string">'success'</span>,
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Your work has been saved'</span>,
    <span class="hljs-attr">showConfirmButton</span>: <span class="hljs-literal">false</span>,
    <span class="hljs-attr">timer</span>: <span class="hljs-number">1500</span>
    })</code></pre>
        </li>

        <li id="custom-animation">
        <div class="ui">
            <p>Custom animation with <a href="https://animate.style/" target="_blank" rel="noreferrer noopener" tabindex="-1" class="nowrap">Animate.css <i class="fa fa-external-link"></i></a></p>
            <button class="show-example-btn" aria-label="Try me! Example: jQuery HTML with custom animation" onclick="executeExample('customAnimation')">
            Try me!
            </button>
        </div>
        <pre data-example-id="customAnimation" data-codepen-css-external="https://cdn.jsdelivr.net/npm/animate.css@4.0.0/animate.min.css" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Custom animation with Animate.css'</span>,
    <span class="hljs-attr">showClass</span>: {
        <span class="hljs-attr">popup</span>: <span class="hljs-string">'animate__animated animate__fadeInDown'</span>
    },
    <span class="hljs-attr">hideClass</span>: {
        <span class="hljs-attr">popup</span>: <span class="hljs-string">'animate__animated animate__fadeOutUp'</span>
    }
    })</code></pre>
        </li>

        <li id="confirm-dialog">
        <div class="ui">
            <p>A confirm dialog, with a function attached to the "Confirm"-button</p>
            <button class="show-example-btn" aria-label="Try me! Example: A confirm dialog, with a function attached to the 'Confirm'-button" onclick="executeExample('warningConfirm')">
            Try me!
            </button>
        </div>
        <pre data-example-id="warningConfirm" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Are you sure?'</span>,
    <span class="hljs-attr">text</span>: <span class="hljs-string">"You won't be able to revert this!"</span>,
    <span class="hljs-attr">icon</span>: <span class="hljs-string">'warning'</span>,
    <span class="hljs-attr">showCancelButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">confirmButtonColor</span>: <span class="hljs-string">'#3085d6'</span>,
    <span class="hljs-attr">cancelButtonColor</span>: <span class="hljs-string">'#d33'</span>,
    <span class="hljs-attr">confirmButtonText</span>: <span class="hljs-string">'Yes, delete it!'</span>
    }).<span class="hljs-title function_">then</span>(<span class="hljs-function">(<span class="hljs-params">result</span>) =&gt;</span> {
    <span class="hljs-keyword">if</span> (result.<span class="hljs-property">isConfirmed</span>) {
        <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>(
        <span class="hljs-string">'Deleted!'</span>,
        <span class="hljs-string">'Your file has been deleted.'</span>,
        <span class="hljs-string">'success'</span>
        )
    }
    })</code></pre>
        </li>

        <li id="dismiss-handle">
        <div class="ui">
            <p>... and by passing a parameter, you can execute something else for "Cancel"</p>
            <button class="show-example-btn" aria-label="Try me! Example: passing a parameter, you can execute something else for 'Cancel'" onclick="executeExample('handleDismiss')">
            Try me!
            </button>
        </div>
        <pre data-example-id="handleDismiss" data-codepen-css-external="https://sweetalert2.github.io/styles/bootstrap4-buttons.css" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-keyword">const</span> swalWithBootstrapButtons = <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">mixin</span>({
    <span class="hljs-attr">customClass</span>: {
        <span class="hljs-attr">confirmButton</span>: <span class="hljs-string">'btn btn-success'</span>,
        <span class="hljs-attr">cancelButton</span>: <span class="hljs-string">'btn btn-danger'</span>
    },
    <span class="hljs-attr">buttonsStyling</span>: <span class="hljs-literal">false</span>
    })

    swalWithBootstrapButtons.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Are you sure?'</span>,
    <span class="hljs-attr">text</span>: <span class="hljs-string">"You won't be able to revert this!"</span>,
    <span class="hljs-attr">icon</span>: <span class="hljs-string">'warning'</span>,
    <span class="hljs-attr">showCancelButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">confirmButtonText</span>: <span class="hljs-string">'Yes, delete it!'</span>,
    <span class="hljs-attr">cancelButtonText</span>: <span class="hljs-string">'No, cancel!'</span>,
    <span class="hljs-attr">reverseButtons</span>: <span class="hljs-literal">true</span>
    }).<span class="hljs-title function_">then</span>(<span class="hljs-function">(<span class="hljs-params">result</span>) =&gt;</span> {
    <span class="hljs-keyword">if</span> (result.<span class="hljs-property">isConfirmed</span>) {
        swalWithBootstrapButtons.<span class="hljs-title function_">fire</span>(
        <span class="hljs-string">'Deleted!'</span>,
        <span class="hljs-string">'Your file has been deleted.'</span>,
        <span class="hljs-string">'success'</span>
        )
    } <span class="hljs-keyword">else</span> <span class="hljs-keyword">if</span> (
        <span class="hljs-comment">/* Read more about handling dismissals below */</span>
        result.<span class="hljs-property">dismiss</span> === <span class="hljs-title class_">Swal</span>.<span class="hljs-property">DismissReason</span>.<span class="hljs-property">cancel</span>
    ) {
        swalWithBootstrapButtons.<span class="hljs-title function_">fire</span>(
        <span class="hljs-string">'Cancelled'</span>,
        <span class="hljs-string">'Your imaginary file is safe :)'</span>,
        <span class="hljs-string">'error'</span>
        )
    }
    })</code></pre>
        </li>

        <li id="custom-image-example">
        <div class="ui">
            <p>A message with a custom image</p>
            <button class="show-example-btn" aria-label="Try me! Example: A message with a custom image" onclick="executeExample('customImage')">
            Try me!
            </button>
        </div>
        <pre data-example-id="customImage" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Sweet!'</span>,
    <span class="hljs-attr">text</span>: <span class="hljs-string">'Modal with a custom image.'</span>,
    <span class="hljs-attr">imageUrl</span>: <span class="hljs-string">'https://unsplash.it/400/200'</span>,
    <span class="hljs-attr">imageWidth</span>: <span class="hljs-number">400</span>,
    <span class="hljs-attr">imageHeight</span>: <span class="hljs-number">200</span>,
    <span class="hljs-attr">imageAlt</span>: <span class="hljs-string">'Custom image'</span>,
    })</code></pre>
        </li>

        <li id="custom-width-padding-background">
        <div class="ui">
            <p>A message with custom width, padding, background and animated Nyan Cat</p>
            <button class="show-example-btn" aria-label="Try me! Example: A message with custom width, padding and background" onclick="executeExample('customWidth')">
            Try me!
            </button>
        </div>
        <pre data-example-id="customWidth" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Custom width, padding, color, background.'</span>,
    <span class="hljs-attr">width</span>: <span class="hljs-number">600</span>,
    <span class="hljs-attr">padding</span>: <span class="hljs-string">'3em'</span>,
    <span class="hljs-attr">color</span>: <span class="hljs-string">'#716add'</span>,
    <span class="hljs-attr">background</span>: <span class="hljs-string">'#fff url(/images/trees.png)'</span>,
    <span class="hljs-attr">backdrop</span>: <span class="hljs-string">`
        rgba(0,0,123,0.4)
        url("/images/nyan-cat.gif")
        left top
        no-repeat
    `</span>
    })</code></pre>
        </li>

        <li id="timer-example">
        <div class="ui">
            <p>A message with auto close timer</p>
            <button class="show-example-btn" aria-label="Try me! Example: A message with auto close timer" onclick="executeExample('timer')">
            Try me!
            </button>
        </div>
        <pre data-example-id="timer" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-keyword">let</span> timerInterval
    <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Auto close alert!'</span>,
    <span class="hljs-attr">html</span>: <span class="hljs-string">'I will close in &lt;b&gt;&lt;/b&gt; milliseconds.'</span>,
    <span class="hljs-attr">timer</span>: <span class="hljs-number">2000</span>,
    <span class="hljs-attr">timerProgressBar</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">didOpen</span>: <span class="hljs-function">() =&gt;</span> {
        <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">showLoading</span>()
        <span class="hljs-keyword">const</span> b = <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">getHtmlContainer</span>().<span class="hljs-title function_">querySelector</span>(<span class="hljs-string">'b'</span>)
        timerInterval = <span class="hljs-built_in">setInterval</span>(<span class="hljs-function">() =&gt;</span> {
        b.<span class="hljs-property">textContent</span> = <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">getTimerLeft</span>()
        }, <span class="hljs-number">100</span>)
    },
    <span class="hljs-attr">willClose</span>: <span class="hljs-function">() =&gt;</span> {
        <span class="hljs-built_in">clearInterval</span>(timerInterval)
    }
    }).<span class="hljs-title function_">then</span>(<span class="hljs-function">(<span class="hljs-params">result</span>) =&gt;</span> {
    <span class="hljs-comment">/* Read more about handling dismissals below */</span>
    <span class="hljs-keyword">if</span> (result.<span class="hljs-property">dismiss</span> === <span class="hljs-title class_">Swal</span>.<span class="hljs-property">DismissReason</span>.<span class="hljs-property">timer</span>) {
        <span class="hljs-variable language_">console</span>.<span class="hljs-title function_">log</span>(<span class="hljs-string">'I was closed by the timer'</span>)
    }
    })</code></pre>
        </li>

        <li id="rtl">
        <div class="ui">
            <p>Right-to-left support for Arabic, Persian, Hebrew, and other RTL languages</p>
            <button class="show-example-btn" aria-label="Try me! Example: A message in Arabic" onclick="executeExample('rtl')">
            Try me!
            </button>
        </div>
        <pre data-example-id="rtl" data-codepen-html="<body dir='rtl'></body>" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'هل تريد الاستمرار؟'</span>,
    <span class="hljs-attr">icon</span>: <span class="hljs-string">'question'</span>,
    <span class="hljs-attr">iconHtml</span>: <span class="hljs-string">'؟'</span>,
    <span class="hljs-attr">confirmButtonText</span>: <span class="hljs-string">'نعم'</span>,
    <span class="hljs-attr">cancelButtonText</span>: <span class="hljs-string">'لا'</span>,
    <span class="hljs-attr">showCancelButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">showCloseButton</span>: <span class="hljs-literal">true</span>
    })</code></pre>
        </li>

        <li id="ajax-request">
        <div class="ui">
            <p>AJAX request example</p>
            <button class="show-example-btn" aria-label="Try me! Example: AJAX request" onclick="executeExample('ajaxRequest')">
            Try me!
            </button>
        </div>
        <pre data-example-id="ajaxRequest" class="code-sample"><code class="lang-javascript hljs language-javascript"><span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
    <span class="hljs-attr">title</span>: <span class="hljs-string">'Submit your Github username'</span>,
    <span class="hljs-attr">input</span>: <span class="hljs-string">'text'</span>,
    <span class="hljs-attr">inputAttributes</span>: {
        <span class="hljs-attr">autocapitalize</span>: <span class="hljs-string">'off'</span>
    },
    <span class="hljs-attr">showCancelButton</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">confirmButtonText</span>: <span class="hljs-string">'Look up'</span>,
    <span class="hljs-attr">showLoaderOnConfirm</span>: <span class="hljs-literal">true</span>,
    <span class="hljs-attr">preConfirm</span>: <span class="hljs-function">(<span class="hljs-params">login</span>) =&gt;</span> {
        <span class="hljs-keyword">return</span> <span class="hljs-title function_">fetch</span>(<span class="hljs-string">`//api.github.com/users/<span class="hljs-subst">${login}</span>`</span>)
        .<span class="hljs-title function_">then</span>(<span class="hljs-function"><span class="hljs-params">response</span> =&gt;</span> {
            <span class="hljs-keyword">if</span> (!response.<span class="hljs-property">ok</span>) {
            <span class="hljs-keyword">throw</span> <span class="hljs-keyword">new</span> <span class="hljs-title class_">Error</span>(response.<span class="hljs-property">statusText</span>)
            }
            <span class="hljs-keyword">return</span> response.<span class="hljs-title function_">json</span>()
        })
        .<span class="hljs-title function_">catch</span>(<span class="hljs-function"><span class="hljs-params">error</span> =&gt;</span> {
            <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">showValidationMessage</span>(
            <span class="hljs-string">`Request failed: <span class="hljs-subst">${error}</span>`</span>
            )
        })
    },
    <span class="hljs-attr">allowOutsideClick</span>: <span class="hljs-function">() =&gt;</span> !<span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">isLoading</span>()
    }).<span class="hljs-title function_">then</span>(<span class="hljs-function">(<span class="hljs-params">result</span>) =&gt;</span> {
    <span class="hljs-keyword">if</span> (result.<span class="hljs-property">isConfirmed</span>) {
        <span class="hljs-title class_">Swal</span>.<span class="hljs-title function_">fire</span>({
        <span class="hljs-attr">title</span>: <span class="hljs-string">`<span class="hljs-subst">${result.value.login}</span>'s avatar`</span>,
        <span class="hljs-attr">imageUrl</span>: result.<span class="hljs-property">value</span>.<span class="hljs-property">avatar_url</span>
        })
    }
    })</code></pre>
        </li>
    </ul>

	<!-- JS -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<!-- <script src="../dist/jquery.fancybox.min.js"></script> -->
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- <script src="https://api.github.com/repos/sweetalert2/sweetalert2?callback=callback"></script> -->
    <!-- <script src="//m.servedby-buysellads.com/monetization.js" defer=""></script> -->
    <script src="https://sweetalert2.github.io/dist/bundle.js" defer=""></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@docsearch/js@3"></script> -->
</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Test </title>

	<meta name="theme-color" content="#f2f4f6">
    
    <link rel="stylesheet" href="https://sweetalert.js.org/assets/css/app.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet">
    <!-- <script src="https://sweetalert.js.org/assets/sweetalert/sweetalert.min.js"></script> -->
</head>
<body>

    <h3 id="examples">Examples</h3>
    <url>https://sweetalert.js.org/guides/#installation</url><br>

    <div class="highlight js"><pre class="editor editor-colors"><div class="line"><span class="source js"><span class="meta function-call js"><span class="entity name function js"><span>swal</span></span><span class="meta arguments js"><span class="punctuation definition arguments begin bracket round js"><span>(</span></span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>Hello&nbsp;world!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="punctuation definition arguments end bracket round js"><span>)</span></span></span></span><span class="punctuation terminator statement js"><span>;</span></span></span></div></pre></div>
    <br>
    <p><button class="preview" onclick="exam1()">Preview Examples 1</button></p>

    <div class="highlight js"><pre class="editor editor-colors"><div class="line"><span class="source js"><span class="meta function-call js"><span class="entity name function js"><span>swal</span></span><span class="meta arguments js"><span class="punctuation definition arguments begin bracket round js"><span>(</span></span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>Here's&nbsp;the&nbsp;title!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>...and&nbsp;here's&nbsp;the&nbsp;text!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="punctuation definition arguments end bracket round js"><span>)</span></span></span></span><span class="punctuation terminator statement js"><span>;</span></span></span></div></pre></div>

    <p><button class="preview" onclick="exam2()">Preview Examples 2</button></p><br>

    <p>And with a third argument, you can add an icon to your alert! There are 4 predefined ones: <code>"warning"</code>, <code>"error"</code>, <code>"success"</code> and <code>"info"</code>.</p>
    <div class="highlight js"><pre class="editor editor-colors"><div class="line"><span class="source js"><span class="meta function-call js"><span class="entity name function js"><span>swal</span></span><span class="meta arguments js"><span class="punctuation definition arguments begin bracket round js"><span>(</span></span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>Good&nbsp;job!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>You&nbsp;clicked&nbsp;the&nbsp;button!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>success</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="punctuation definition arguments end bracket round js"><span>)</span></span></span></span><span class="punctuation terminator statement js"><span>;</span></span></span></div></pre></div>

    <p><button class="preview" onclick="exam3()">Preview Examples 3</button></p><br>

    <p>With this format, we can specify many more options to customize our alert. For example we can change the text on the confirm button to <code>"Aww yiss!"</code>:</p>
    <div class="highlight js"><pre class="editor editor-colors"><div class="line"><span class="source js"><span class="meta function-call js"><span class="entity name function js"><span>swal</span></span><span class="meta arguments js"><span class="punctuation definition arguments begin bracket round js"><span>(</span></span><span class="meta brace curly js"><span>{</span></span></span></span></span></div><div class="line"><span class="source js"><span class="meta function-call js"><span class="meta arguments js"><span>&nbsp;&nbsp;title</span><span class="keyword operator js"><span>:</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>Good&nbsp;job!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span></span></span></span></div><div class="line"><span class="source js"><span class="meta function-call js"><span class="meta arguments js"><span>&nbsp;&nbsp;text</span><span class="keyword operator js"><span>:</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>You&nbsp;clicked&nbsp;the&nbsp;button!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span></span></span></span></div><div class="line"><span class="source js"><span class="meta function-call js"><span class="meta arguments js"><span>&nbsp;&nbsp;icon</span><span class="keyword operator js"><span>:</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>success</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span></span></span></span></div><div class="line"><span class="source js"><span class="meta function-call js"><span class="meta arguments js"><span>&nbsp;&nbsp;button</span><span class="keyword operator js"><span>:</span></span><span>&nbsp;</span><span class="string quoted double js"><span class="punctuation definition string begin js"><span>"</span></span><span>Aww&nbsp;yiss!</span><span class="punctuation definition string end js"><span>"</span></span></span><span class="meta delimiter object comma js"><span>,</span></span></span></span></span></div><div class="line"><span class="source js"><span class="meta function-call js"><span class="meta arguments js"><span class="meta brace curly js"><span>}</span></span><span class="punctuation definition arguments end bracket round js"><span>)</span></span></span></span><span class="punctuation terminator statement js"><span>;</span></span></span></div></pre></div>
    
    <p><button class="preview" onclick="exam4()">Preview Examples 4</button></p><br>
	<!-- JS -->
	<!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

        function exam1(){
            swal("Hello world!");
        }

        function exam2(){
            swal("Here's the title!", "...and here's the text!");
        }

        function exam3(){
            swal({
                title: "Good job!",
                text: "You clicked the button!",
                icon: "success",
            });
        }

        function exam4(){
            swal({
                title: "Good job!",
                text: "You clicked the button!",
                icon: "success",
                button: "Aww yiss!",
            });
        }
        
    </script>
</body>
</html>
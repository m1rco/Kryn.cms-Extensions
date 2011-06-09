{addJs file="codehighlighter/highlight.pack.js"}
{addCss file="codehighlighter/github.css"}
{addCss file="codehighlighter/base.css"}
{addHeader content="<script>hljs.initHighlightingOnLoad();</script>"}

<div class="codehighlighter-block">
  {if $content.title}
    <div class="codehighlighter-block-title">{$content.title}</div>
  {/if}

  <pre><code>{$content.content|htmlentities}</code></pre>
</div>

# html-tags-statistics

The main function of this tool is to determine if there are odd HTML tags in a file, which would mean that some tags where not properly closed.
This tool also generates a statistical report on the use of HTML tags.


- The labels are automatically detected by means of regular expressions.
- [Self closing tags](#self-closing-tags) are excluded from the alert.


## Report columns
- **Tag Name:** The found tag name
- **Open:** Number of opening tags, ex.: &lt;a href="..."&gt;, &lt;br /&gt;, &lt;img src=&quot;...&quot; /&gt;, etc.
- **Close:** Number of closing tags, ex.: &lt;/a&gt;, &lt;/tr&gt;, etc.
- **Frequency:** The porcentage of ocurrence of this tag aginst the most used tag.


## Self closing tags

List of all valid HTML [void-elements](https://www.w3.org/TR/html5/syntax.html#void-elements) that are excluded from the alert report.
- area
- base
- br
- col
- command
- embed
- hr
- img
- input
- keygen
- link
- meta
- param
- source
- track
- wbr

## Tip: Compile multiple files in a single big one

In order make a quick validation on multiple files you can compile all the individual files in a single one and upload that file to the tool.

- **Pros**: You can quickly validate a large number of files in a single report.
- **Cons**: You will not know in which file is the specific missing tag.

### Windows

1. Open a [CLI] windows on the parent folder containing all the files to compile.
2. Run this command: 
```
$ copy *.htm compiled_list.txt
```
3. All the files with **HTM** extension will be compiled inside the new file **compiled_list.txt**.
4. Upload **compiled_list.txt** file to the tool.
5. Done!

### Mac

TBD





[CLI]: https://www.youtube.com/watch?v=X3NtiEbNe-c
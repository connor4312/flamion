Flamion
=========

Flamion is the Minecraft panel to rule them all. It has unmatched administrative and user-end powers.

Components
-
- The Control panel
 - HTML
 - CSS via LESS with Bootstrap
 - Javascript with jQuery and Bootstrap
 - PHP with Laravel
 - MySQL via the Laravel Query Builder and Eloquent ORM
- The Reactor
 -  Python  
- Licensing Sever
 - Javascript via Node.js

Developers
-----------

- Lead Developer: Connor Peet
- Backend Developer: Jeffrey Angenent
- Backend Developer: **open**
- Reactor (Python) Developer: Tom Barker
- Android Mobile Developer: Zack Pollard
- iOS Mobile Developer: Jeffrey Angenent
- Frontend Developer: Alex Bandtock
- Frotent Developer: Branden Gammon
- Frontend Developer: **open**

Dev Tools (Recommended)
--------------
- SublimeText or your favorite code editor
- FileZilla or similar FTP client
- SourceTree, unless you love the command line
- Chrome, or Firefox with Firebug

Who to Talk to
==
If you have any questions at all, ask in the Skype group. If you are not in the Skype group, add connor4312.

What to Do
==
Any cards assignd to you in our Jira, go to ferrous.atlassian.net then Agile > Flamion. If you don't yet have an account, ask in Skype.

Editing Stuff
=========
In General
---
For each feature/page you work, on... cut a branch. Edit, upload to the FTP server, debug, make sure whatever you edited it's working. After it works, commit. Repeat until the page is done; you should commit about every 15-45 minutes. After the page or feature is done and polished, submit a pull request. Your code will be reviewed by the Lead Developer or a Backend Developer. *(note: even if you are one of these people, it is recommended to have another person review your changes if possible)*. If it's good, it'll be merged. If not, you'll get comments on things to fix.

LESS/CSS
---
You **don't** need to have a LESS compiler on your system, in fact we recommend that you don't use one, even if you have one. We already have a PHP LESS compiler in the control panel. After you edit some CSS (in `app/storage/less`) upload it to FTP, go to the Panel > Panel Options > Advanced > Compile LESS. This will automagically update the CSS and compile for you.

Unit Testing
---
If you want to unit test, fine, if not, don't. Not strictly TDD at the moment.
  

    
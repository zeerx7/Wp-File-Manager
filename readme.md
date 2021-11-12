# FileManager Plugin for Wordpress</br>

<img src="https://i.ibb.co/NVnS0Q6/filemanager846.png" />

## Description

With FileManager Plugin for Wordpress, you can make workplace and share link and allow read and write permision to users and public.</br>
You can upload files and directorie, create blank file and directorie, copy and move files and directories, rename and delete files and directories, create zip from files and directories, get info and search through files and directories.

## Installation

1. Unzip file in your `/wp-content/plugins` directorie.
2. Activate plugin in wp-admin.
3. Go to the admin setting page, and add workplace and give the read and write autorization to your user.
3. Use short code [filemanager-shortcode] in page.
4. Set the permision of `oauth` file in `/includes/tokens` to read and write for www-data.

## Support

Support for PDF, JPEG, JPG, BMP, PNG, GIF, MP4, MKV, TXT, HTML, PHP, JS, LOG.

## Changelog

- 0.7 - eatch path link have been changed to unique string, now support all type of special character in link.
- 0.65 - Add user right to share link.
- 0.6 - Add tree view.
- 0.55 - Fix ajax function called to many time, add a search button, add select all.
- 0.5 - Various fix, all btn are tested and working with php funtion, url with double slash, no smoothState on download link.
- 0.15 - add Pagination in scandir().
- 0.14 - Add public share link to file.
- 0.14 - Add unique key to download link.
- 0.13 - Add copy button.
- 0.13 - Menu top is now part of smoothState wrapper.
- 0.12 - Add codemirror.js, code editor.
- 0.11 - Add upload files and directorie, create file and a move button.
- 0.10 - Add admin setting page to create workplace.
- 0.9 - Add dragable info windows.
- 0.8 - Multi-upload anywhere while you browse.
- 0.7 - Add rename fonction.
- 0.6 - Use smoothState.
- 0.5 - Add PDF viewer and view image.
- 0.4 - Add multi-uplaod fonction.
- 0.3 - Add video player.
- 0.2 - Add select checkbox use table and show size.
- 0.1 - Init version.

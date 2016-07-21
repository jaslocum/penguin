# penguin
image server

version  - unversioned

penguin with attitude
Usage:

Web interface to edit|create a {category}/{key} bucket or an {image_id} bucket:

     http://penguin/{category}/{key}/edit[?description={description}]

     http://penguin/{image_id}/edit[?description={description}]

     http://penguin/create[?description={description}]


Return the first image or an image by filename from a {category}/{key} bucket or return an image from an {image_id} bucket:

     http://penguin/{category}/{key}[/{filename}]

     http://penguin/{image_id}


Delete an image from a {category}/{key} bucket or an {image_id} bucket:

     http://penguin/{category}/{key}/[{filename}/]destroy|delete

     http://penguin/{image_id}/destroy|delete


Restore an image from a {category}/{key} bucket or an {image_id} bucket:

     http://penguin/{category}/{key}/[{filename}/]restore|undelete

     http://penguin/{image_id}/restore|undelete


Web interface to define a {category} for images:

      http://penguin/category

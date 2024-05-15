#!/bin/sh

# [Question] Why do we need this file ?
# - Because we cannot have to install node modules or build an react application on the pipeline or
# on the machine during the bootstrap
# [Question] Why run this file ?
# - Run it on unix/linux os if you want update the frontend application developed in react.
# - /bin/sh update-frontend.sh

# 1. Generating the build of frontend application
cd 3marias/public/application
npm run build

cp -r build/index.html ../../resources/views/
mv ../../resources/views/index.html ../../resources/views/welcome.blade.php
rm build/index.html

# 2. Moving the static folder
rm -R ../static
mkdir ../static
cp -r build/static/* ../static
cp -r build/* ../
rm -R build

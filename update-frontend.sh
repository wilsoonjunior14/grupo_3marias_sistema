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

# 2. Moving the static folder
cp -r build/static/* ../static
cp -r build/manifest.json ../
rm build/manifest.json
rm -R build/static

# 3. Moving the other files
cp -r build/* ../../resources/views/
rm -R build

# 4. Removing the old welcome.blade.php
cd ../../resources/views
mv index.html welcome.blade.php
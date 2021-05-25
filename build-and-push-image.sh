#!/bin/bash

if [[ -z $1 ]]; then
  echo "Image Tag version (e.g., 1.0.0) is required."
  exit 1
fi

echo "Building Image"

base_dir="`dirname "$0"`"

echo "Base Dir:" $base_dir

ecrImageRepo=286062821256.dkr.ecr.ap-southeast-1.amazonaws.com/pass_app
imageVersion=$1
awsProfile=viva-movement-pass

docker build -f Dockerfile.prod -t pass_app:$imageVersion \
               -t $ecrImageRepo:$imageVersion


### setup viva-movement-pass aws profile
$(aws ecr get-login --no-include-email --region ap-southeast-1 --profile $awsProfile | sed 's|https://||')
docker push $ecrImageRepo:$imageVersion

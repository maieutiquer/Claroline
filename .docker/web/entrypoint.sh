#!/bin/bash

set -e

# Wait for MySQL to respond, depends on mysql-client
echo "Waiting for $DB_HOST..."
while ! mysqladmin ping -h "$DB_HOST" --silent; do
    echo "MySQL is down"
    sleep 1
done

echo "MySQL is up"

if [ -f installed ]; then
   echo "ClarolineConnect is already installed"
else
  echo "Executing configuration script"
  php bin/configure
  php bin/check

  composer install --no-dev --optimize-autoloader

  npm install

  composer build

  php bin/console claroline:install

  if [[ -v PLATFORM_NAME ]]; then
    echo "Changing platform name to $PLATFORM_NAME";
    sed -i "/name: claroline/c\name: $PLATFORM_NAME" files/config/platform_options.yml
  fi

  if [[ -v PLATFORM_SUPPORT_EMAIL ]]; then
    echo "Changing platform support email to $PLATFORM_SUPPORT_EMAIL";
    sed -i "/support_email: null/c\support_email: $PLATFORM_SUPPORT_EMAIL" files/config/platform_options.yml
  fi

  USERS=$(mysql $DB_NAME -u $DB_USER -p$DB_PASSWORD -h $DB_HOST -se "select count(*) from claro_user")

  # BEGIN debug user creation
  echo "USERS: $USERS"
  
  if ["$USERS" == "0"]; then
    echo "USERS is 0"
  fi

  echo "ADMIN_FIRSTNAME: $ADMIN_FIRSTNAME"
  
  if [-v ADMIN_FIRSTNAME]; then
    echo "-v ADMIN_FIRSTNAME is truthy"
  fi
  
  if [ "$USERS" == "0" ] && [ -v ADMIN_FIRSTNAME ]; then
    echo "USERS is 0 and -v ADMIN_FIRSTNAME is truthy"
  fi
  # END debug user creation

  if [ "$USERS" == "0" ] && [ -v ADMIN_FIRSTNAME ] && [ -v ADMIN_LASTNAME ] && [ -v ADMIN_USERNAME ] && [ -v ADMIN_PASSWORD ]  && [ -v ADMIN_EMAIL ]; then
    echo '*********************************************************************************************************************'
    echo "Creating default admin user : $ADMIN_FIRSTNAME $ADMIN_LASTNAME $ADMIN_USERNAME $ADMIN_PASSWORD $ADMIN_EMAIL"
    echo '*********************************************************************************************************************'

    php bin/console claroline:user:create -a $ADMIN_FIRSTNAME $ADMIN_LASTNAME $ADMIN_USERNAME $ADMIN_PASSWORD $ADMIN_EMAIL
  else
    echo 'ClarolineConnect installed without an admin account'
  fi

  touch installed
fi

# temporary workaround for missing folders
mkdir -p var/sessions
mkdir -p public/uploads

echo "Setting correct file permissions"
chmod -R 777 var/cache files/config var/log var/sessions files public/uploads

echo "Disabling SAML in files/config/bundles.ini"
# LightSaml\SymfonyBridgeBundle\LightSamlSymfonyBridgeBundle = true
# LightSaml\SpBundle\LightSamlSpBundle = true
# sed 's/LightSaml\SymfonyBridgeBundle\LightSamlSymfonyBridgeBundle = true/LightSaml\SymfonyBridgeBundle\LightSamlSymfonyBridgeBundle = false/' files/config/bundles.ini
# sed 's/LightSaml\SpBundle\LightSamlSpBundle = true/LightSaml\SpBundle\LightSamlSpBundle = false/' files/config/bundles.ini

exec "$@"

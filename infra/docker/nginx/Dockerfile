FROM node:14-alpine as node
FROM nginx:1.20-alpine
LABEL maintainer="soregashi27"
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

ENV TZ=UTC

RUN apk update && \
  apk add --update --no-cache --virtual=.build-dependencies g++

# node command
COPY --from=node /usr/local/bin /usr/local/bin
# npm command
COPY --from=node /usr/local/lib /usr/local/lib
# yarn command
COPY --from=node /opt /opt
# nginx config file will be output to /etc/nginx/conf.d/ by envsubst
COPY ./infra/docker/nginx/default.conf /etc/nginx/templates/default.conf.template
COPY ./infra/docker/nginx/nginx.conf /etc/nginx/nginx.conf
# need to nginx image
COPY ./backend/public /work/backend/public

WORKDIR /work/backend

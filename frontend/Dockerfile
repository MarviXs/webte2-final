# production or development
ARG BUILD_ENV=production

# Base stage
FROM node:lts-bullseye-slim as base

ARG VITE_API_URL
ENV VITE_API_URL=$VITE_API_URL

ARG VITE_APP_URL
ENV VITE_APP_URL=$VITE_APP_URL

ENV NODE_ENV ${BUILD_ENV}
WORKDIR /app
COPY package*.json ./
RUN npm install

# Development build
FROM base as development
COPY . .
CMD ["npm", "run", "dev"]

# Build stage for production
FROM base as build-stage
COPY . .
RUN npm run build

# Production stage
FROM nginx:stable-alpine as production
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build-stage /app/dist /usr/share/nginx/html
CMD ["nginx", "-g", "daemon off;"]

# Use the build argument to decide which stage to build
FROM ${BUILD_ENV} as final
EXPOSE 8080
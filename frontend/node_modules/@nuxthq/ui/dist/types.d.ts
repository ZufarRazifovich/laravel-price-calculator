
import { ModuleOptions } from './module'

declare module '@nuxt/schema' {
  interface NuxtConfig { ['ui']?: Partial<ModuleOptions> }
  interface NuxtOptions { ['ui']?: ModuleOptions }
}

declare module 'nuxt/schema' {
  interface NuxtConfig { ['ui']?: Partial<ModuleOptions> }
  interface NuxtOptions { ['ui']?: ModuleOptions }
}


export { ModuleOptions, default } from './module'

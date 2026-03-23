import eslint from '@eslint/js'
import stylistic from '@stylistic/eslint-plugin'
import { defineConfig } from 'eslint/config'
import importPlugin from 'eslint-plugin-import'
import reactPlugin from 'eslint-plugin-react'
import reactHooksPlugin from 'eslint-plugin-react-hooks'
import tseslint from 'typescript-eslint'

export default defineConfig(
  { ignores: ['dist/**', 'node_modules/**'] },

  eslint.configs.recommended,
  stylistic.configs.recommended,

  tseslint.configs.strict,
  tseslint.configs.stylisticTypeChecked,
  tseslint.configs.recommendedTypeChecked,
  reactHooksPlugin.configs.flat.recommended,

  {
    files: ['**/*.ts', '**/*.tsx'],
    languageOptions: {
      parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module',
        projectService: {
          defaultProject: 'tsconfig.app.json',
        },
      },
    },
    plugins: {
      import: importPlugin,
      react: reactPlugin,
    },
    settings: {
      react: {
        version: 'detect',
      },
    },
    rules: {
      ...reactPlugin.configs.recommended.rules,
      ...reactPlugin.configs['jsx-runtime'].rules,
      '@stylistic/jsx-curly-brace-presence': 0,
      '@typescript-eslint/no-misused-promises': [
        'error',
        {
          checksVoidReturn: false,
        },
      ],
      'react/self-closing-comp': 'error',
      'no-console': 'error',
      'import/order': ['error', {
        'newlines-between': 'always',
        'groups': ['external', 'builtin', 'internal', ['sibling', 'parent'], 'index'],
        'pathGroups': [
          { pattern: '@/**', group: 'internal', position: 'after' },
        ],
        'pathGroupsExcludedImportTypes': [],
        'alphabetize': { order: 'asc', caseInsensitive: true },
      }],
      'sort-imports': ['error', {
        ignoreCase: true,
        ignoreDeclarationSort: true,
        ignoreMemberSort: false,
      }],
    },
  },
  {
    files: ['src/routes/**/*.tsx'],
    rules: {
      'react-refresh/only-export-components': 'off',
    },
  },
)

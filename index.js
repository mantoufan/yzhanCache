#!/usr/bin/env node
const { NAME_FN, NAME_C_FN } = require('./data')
const fs = require('fs')
const shelljs = require('shelljs')
const yargs = require('yargs').option('i', {
  alias : 'input',
  demand: true,
  // default: 'input.js', // '../mtfApp/web/USR/j.js'
  default: '../mtfApp/web/USR/j.js',
  describe: 'input file',
  type: 'string'
}).option('o', {
  alias : 'output',
  demand: true,
  default: 'output.js',
  describe: 'output file',
  type: 'string'
}).usage('Usage: yzjqls [options]')
.example('yzjqls -i input.js -o output.js', 'Create jQuery-like Library named output.js from input.js')
.help('h')
.alias('h', 'help')
.epilog('© YZhan')
.argv
const js = shelljs.cat(yargs.i)
const reg = new RegExp('\\.(?<name>(' + NAME_FN.concat(NAME_C_FN).join('|') + '))', 'g')
const res = js.matchAll(reg)
const s = new Map
for(const r of res) {
  s.set(r.groups.name, (s.get(r.groups.name) || 0) + 1)
}
const ans = Array.from(s.entries()).sort((a, b) => b[1] - a[1])
shelljs.echo(ans.length + ' Found：')
shelljs.echo(ans)
fs.writeFile(yargs.o, JSON.stringify(ans), err=>err)
shelljs.echo('Exported: ' + yargs.o)
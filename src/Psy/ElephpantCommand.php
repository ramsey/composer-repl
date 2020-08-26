<?php

/**
 * This file is part of ramsey/composer-repl
 *
 * ramsey/composer-repl is open source software: you can distribute
 * it and/or modify it under the terms of the MIT License
 * (the "License"). You may not use this file except in
 * compliance with the License.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace Ramsey\Dev\Repl\Psy;

use Psy\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ElephpantCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('🐘')
            ->setAliases(['elephpant'])
            ->setDescription('¯\_(ツ)_/¯')
            ->setHelp('¯\_(ツ)_/¯');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            <<<'EOF'
            <fg=blue>
                                ^;)|` `-;;-`
                              -uwu/;lPMMMMMMa^
                         .-/>lv_\fWMMMMMMMMMMQ-|NNBWQQQMMMMMQBmD0v=-`
                     ;JXMMMMMMMMMMMMMMMMMMMMMMm XMMMMMMMMMMMMMMMMMMMMP1.          -;^
                   lWMMWGPPAQMMMMMMMMMMMMMMMMMM:>MMMMMMMMMMMMMMMMMMMMMMMw- -::^ `;1rvl^
                  aMMD-\JwGPdMMMMMMMMMMMMMMMMMMr-MMMMMMM@DWMMMMMMMMMMMMMMMc      `:=)`
                 ;MMMQNMQ0raMMMMMMMMMMMMMMMMMMMu.MMMMMMv  XMMMMMMMMMMMMMMMMy       ``
                 cMMMMMq.)l7vMMMMMMMMMMMMMMMMMMl`;|lXMM^  ::;|lRMd;:::;\>oQMl
                 0MMMMMWrW0_*MMMQMMMTMMMMMMMMMW.}s:  aD  ]TaI  \M+  0kac  :MW
                 TMMMMMMMKKqMMMM@r770MMMMMMMMD,1MMi  x| `QMML  oQ` /MMMB` -MM,
                 FMMMMMMMMMMMMMMMM:GMMMMFr=:` 7vl: `zW  )MMM- `Qz  *ct)` :qMM-
                 FMMMMMMMMMMMMMMMP/MMMM:/GV  :)=>}xQMg==KMMM>)2M, `))=?cAMMMN
                 FMMMMMMMMMMMMMQc=QMMMM2NM; `WMMMMMMMMMMMMMMMMMX  cMMMMMMMMQ:
                 FMMMMMMMMD>-^. -=v07QMMMMWNWMMMMMMMMMMMMMMMMMMMNNMMMMMMMMMQ~
                 FMMMMMMM=      MQ@v WMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM,
                 FMMMMMMD       MMMK WMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM,
                 FMMMMMMl       MMMK WMMMMMMMMMMMMM@`T@AA@Pa:oMMMMMMMMMMMMMM,
                 FMMMMMM:       KKMK WMMMMMMMMMMMMMP    :>z2.tMMMMMMMMMMMMMM,
                 FMMMMMM.       bXVK WMMMMMMMMMMMMMP    |qcM-tMMWXMMMMMMMMMM,
                 FMMMMMQ       [Qcyv qPLPkPMMMMMMMMP    #waJ,lkoTqoNMMMMMMMM,
                 FMMMMM@       ?2NMt uVKMqvaqMMMMMMP   `QvMq /EoMMLasMMMMMMM,
                 0MMMMMu        .7Vv_MvMMvMMvMMMMMMa    ^-ka^mzQMnNMXTMMMMMM.
                 `*rv}/`            `oiQWzMMu#MMMQf.        `-7cM3BMmYMMMEo_
                                       `\^;;-)/:^               `^`^`,~.`


                    This implementation of PsySH has Super ElePHPant Powers!
                              https://afieldguidetoelephpants.net
            </>
            EOF,
        );

        return 0;
    }
}

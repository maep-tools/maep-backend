
class param_B:
  def __init__(self, stages, seriesBack, stochastic, eps_risk, commit, parallel, param_opf, gates):
    self.st = stages
    self.sb = seriesBack
    self.sc = stochastic
    self.er = eps_risk
    self.cm = commit
    self.pl = parallel
    self.pf = param_opf
    self.gt = gates

class param_F:
  def __init__(self, stages, seriesForw, max_iter, results, param_opf, gates):
    self.st = stages
    self.sf = seriesForw
    self.mi = max_iter
    self.er = results
    self.pf = param_opf
    self.gt = gates